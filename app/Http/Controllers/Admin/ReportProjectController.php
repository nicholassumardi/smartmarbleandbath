<?php

namespace App\Http\Controllers\Admin;

use PDF;
use App\Models\Approval;
use App\Models\City;
use App\Models\Coa;
use App\Models\Vendor;
use App\Models\Brand;
use App\Models\Country;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductShading;
use App\Models\Project;
use App\Models\Delivery;
use App\Models\Dropshipper;
use App\Models\Supplier;
use App\Models\Notification;
use App\Models\ProjectPay;
use Illuminate\Http\Request;
use App\Models\ProjectSample;
use App\Models\ProjectSampleProduct;
use App\Models\ProjectSale;
use App\Models\ProjectSaleProduct;
use App\Models\ProjectPurchase;
use App\Models\ProjectPurchaseProduct;
use App\Models\ProjectPayment;
use App\Models\ProjectProforma;
use App\Models\ProjectProduct;
use App\Models\ProjectDelivery;
use App\Models\ProjectDeliveryProduct;
use App\Models\ProjectDeliveryTrack;
use App\Models\ProjectShipment;
use App\Models\ProjectShipmentProduct;
use App\Models\ProjectShipmentTrack;
use App\Models\ProjectProduction;
use App\Models\ProjectWarehouse;
use App\Models\ProjectWarehouseProduct;
use App\Models\ProjectPurchaseReturn;
use App\Models\ProjectPurchaseReturnProduct;
use App\Models\ProjectSaleShading;
use App\Models\ProjectSaleReturn;
use App\Models\ProjectSaleReturnProduct;
use App\Models\ProjectQuotation;
use App\Models\ProjectQuotationProduct;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\ProjectConsultantMeeting;
use App\Models\ProjectNegotiation;
use App\Models\ProjectTroubleshooting;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\UserRole;
use App\Jobs\EmailProcess;


class ReportProjectController extends Controller {
    
    public function index() 
    {
		$data = [
			'title'   => 'Sales Project',
			'country' => Country::where('status', 1)->get(),
			'city'    => City::all(),
			'bank' => Coa::where('parent_id', 8)->where('status', 1)->get(),
			'customer' => Customer::where('type', 2)->get(),
			'content' => 'admin.report.project.project'
		];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function datatable(Request $request) 
    {
		$uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
		$uri = explode('/', $uri_path);
		
        $column = [
            'id',
            'code',
            'user_id',
            'name',
            'progress'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Project::count();
        
        $query_data = Project::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('name', 'like', "%$search%")
                            ->orWhere('code', 'like', "%$search%");
                    });
                }  
            })
            ->offset($start)
            ->limit($length)
			->orderBy('created_at','DESC')
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Project::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('name', 'like', "%$search%")
                            ->orWhere('code', 'like', "%$search%");
                    });
                }      
            })
            ->count();

        $response['data'] = [];
		
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
                if($val->progress == 100) {
					$action = '<a href="' . url('admin/report/project/details/progress/' . $val->id) . '" class="btn bg-success btn-sm" data-popup="tooltip" title="Detail"><i class="icon-search4"></i></a>';

                    $progress = '
                        <div class="progress" style="height:0.875rem;">
                            <div class="progress-bar progress-bar-striped bg-teal" style="width:100%">
                                <span class="font-weight-bold text-uppercase">
                                    <span style="font-size:13px;">' . $val->progress . '%</span>
                                </span>
                            </div>
                        </div>
                    ';
                } else {
					$action = '<a href="' . url('admin/report/project/details/progress/' . $val->id) . '" class="btn bg-warning btn-sm" data-popup="tooltip" title="Progress"><i class="icon-zoomin3"></i></a>';

                    $progress = '
                        <div class="progress" style="height:0.875rem;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" style="width:100%;">
                                <span class="font-weight-bold text-uppercase">
                                    <span style="font-size:13px;">' . $val->progress . '%</span>
                                </span>
                            </div>
                        </div>
                    ';
                }

                $response['data'][] = [
                    $nomor,
                    $val->code,
                    $val->user->name,
                    $val->name,
                    $progress,
                    $action
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
	
	public function progress(Request $request, $id)
    {
        $query = Project::find($id);
        $step  = $request->submit;
        
        if(!$query) {
            abort(404);
        }
		
		$uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
		$uri = explode('/', $uri_path);

        if($request->has('_token') && session()->token() == $request->_token) {
            switch($step) {
                case 'step-1':
                    $validation = Validator::make($request->all(), [
                        'country_id'     => 'required',
                        'city_id'        => 'required',
                        'name'           => 'required',
                        'customer_id'    => 'required',
                        'timeline'       => 'required',
                        'manager'        => 'required',
                        'consultant'     => 'required',
                        'owner'          => 'required',
						'bank_id'    	 => 'required',
                        'payment_method' => 'required',
						'detail_payment' => 'required',
                        'supply_method'  => 'required',
                        'ppn'            => 'required'
                    ], [
                        'country_id.required'     => 'Please select a country.',
                        'city_id.required'        => 'Please select a city.',
                        'name.required'           => 'Name cannot be empty.',
                        'customer_id.required'    => 'Customer cannot be empty.',
                        'timeline.required'       => 'Timeline cannot be empty',
                        'manager.required'        => 'Project manager cannot be empty',
                        'consultant.required'     => 'Consultant name cannot be empty',
                        'owner.required'          => 'Owner cannot be empty',
						'bank_id.required' 		  => 'Please select a bank destination.',
                        'payment_method.required' => 'Please select a payment method.',
						'detail_payment.required' => 'Please select a detail payment method.',
                        'supply_method.required'  => 'Please select a supply method.',
                        'ppn.required'            => 'Please select a PPN.'
                    ]);
                    break;

                case 'step-2':
                    $validation = Validator::make($request->all(), [
                        'product_id' => 'required|array'
                    ], [
                        'product_id.required' => 'Please add min 1 product.',
                        'product_id.array'    => 'product_id must be array.'
                    ]);
                    break;

                case 'step-3':
                    $validation = Validator::make($request->all(), [
                        'consultant_date'   => 'required|array',
                        'consultant_person' => 'required|array',
                        'consultant_result' => 'required|array'
                    ], [
                        'consultant_date.required'   => 'Date cannot empty.',
                        'consultant_date.array'      => 'Date must be array.',
                        'consultant_person.required' => 'Person cannot empty.',
                        'consultant_person.array'    => 'Person must be array.',
                        'consultant_result.required' => 'Result cannot empty.',
                        'consultant_result.array'    => 'Result must be array.'
                    ]);
                    break;

                case 'step-4':
                    $validation = Validator::make($request->all(), [
                        'product_recommended_price' => 'required|array',
						'product_best_price' => 'required|array',
                        'product_discount' => 'required|array'
                    ], [
                        'product_recommended_price.required' => 'Recommended price cannot empty.',
                        'product_recommended_price.array'    => 'Recommended price must be array.',
						'product_best_price.required' => 'Best Price cannot empty.',
                        'product_best_price.array'    => 'Best Price must be array.',
                        'product_discount.required' => 'Discount cannot empty.',
                        'product_discount.array'    => 'Discount must be array.'
                    ]);
                    break;

                case 'step-5':
                    $validation = Validator::make($request->all(), [
						'sample_sent_date'	=> 'required',
						'sample_return_date'=> 'required',
                        'sample_product_id' => 'required|array',
                        'sample_qty'        => 'required|array',
						'sample_unit'       => 'required|array',
                        'sample_size'       => 'required|array'
                    ], [
						'sample_sent_date.required' 	=> 'Sample sent date cannot empty.',
						'sample_return_date.required' 	=> 'Sample return date cannot empty.',
                        'sample_product_id.required' 	=> 'Please select a product.',
                        'sample_product_id.array'    	=> 'Product id must be array.',
                        'sample_qty.required'        	=> 'Qty cannot empty.',
                        'sample_qty.array'           	=> 'Qty must be array.',
						'sample_unit.required'       	=> 'Unit cannot empty.',
                        'sample_unit.array'          	=> 'Unit must be array.',
                        'sample_size.required'       	=> 'Size cannot empty.',
                        'sample_size.array'          	=> 'Size must be array.'
                    ]);
                    break;

                case 'step-6':
                    $validation = Validator::make($request->all(), [
                        'negotiation_date'   => 'required|array',
                        'negotiation_person' => 'required|array',
                        'negotiation_result' => 'required|array'
                    ], [
                        'negotiation_date.required'   => 'Date cannot empty.',
                        'negotiation_date.array'      => 'Date must be array.',
                        'negotiation_person.required' => 'Person cannot empty.',
                        'negotiation_person.array'    => 'Person must be array.',
                        'negotiation_result.required' => 'Result cannot empty.',
                        'negotiation_result.array'    => 'Result must be array.'
                    ]);
                    break;

                case 'step-7':
                    $validation = Validator::make($request->all(), [
						'sales_id' 	=> 'required',
                        'submit' 	=> 'required'
                    ], [
						'sales.required'  => 'Sales cannot empty.',
                        'submit.required' => 'Step cannot empty.'
                    ]);
                    break;

                case 'step-8':
                    $validation = Validator::make($request->all(), [
						'sop_id'		 => 'required',
                        'file'         	 => 'required|mimes:jpg,jpeg,png,pdf',
                        'date_create'    => 'required',
                        'payment'        => 'required',
                        'payment_method' => 'required',
                        'nominal'        => 'required'
                    ], [
						'sop_id'				  => 'Sales cannot empty',
                        'file.required'           => 'File cannot empty.',
                        'file.mimes'              => 'File must have an extension jpg, jpeg, png, and pdf.',
						'date_create.required'    => 'Date invoice created cannot empty.',
                        'payment.required'        => 'Please select a payment.',
                        'payment_method.required' => 'Please select a payment method.',
                        'nominal.required'        => 'Nominal cannot empty.'
                    ]);
                    break;
				
				case 'step-9':
                    $validation = Validator::make($request->all(), [
						'sales_po' 			=> 'required',
						'supplier_id' 		=> 'required',
						'factory_name'		=> 'required',
						'on_behalf'			=> 'required',
						'delivery_address'	=> 'required',
						'courier_method'	=> 'required',
						'country_id'		=> 'required',
						'city_id'			=> 'required',
						'pic_name'			=> 'required',
						'pic_number'		=> 'required',
						'payment_method'	=> 'required',
						'price'				=> 'required',
						'currency'			=> 'required',
						'product_id'		=> 'required|array',
						'product_unit'		=> 'required|array',
						'product_qty'		=> 'required|array',
						'product_price'		=> 'required|array',
                        'submit' 			=> 'required'
                    ], [
						'sales_po.required' 				=> 'Sales cannot empty.',
						'supplier_id.required' 				=> 'Supplier cannot empty.',
						'factory_name.required'				=> 'Factory name cannot empty.',
						'on_behalf.required'				=> 'On behalf info cannot empty.',
						'delivery_address.required'			=> 'On behalf address cannot empty.',
						'courier_method.required'			=> 'Courier info cannot empty.',
						'country_id.required'				=> 'Country cannot empty.',
						'city_id.required'					=> 'City cannot empty.',
						'pic_name.required'					=> 'PIC Name cannot empty.',
						'pic_number.required'				=> 'PIC Number cannot empty.',
						'city_id.required'					=> 'City cannot empty.',
						'payment_method.required'			=> 'Payment method cannot empty.',
						'price.required'					=> 'Price cannot empty.',
						'currency.required'					=> 'Currency cannot empty.',
						'product_id.required'				=> 'Product cannot empty.',
						'product_id.array'					=> 'Product must be array.',
						'product_unit.required'				=> 'Product unit cannot empty.',
						'product_unit.array'				=> 'Product unit must be array.',
						'product_qty.required'				=> 'Product qty cannot empty.',
						'product_qty.array'					=> 'Product qty must be array.',
						'product_price.required'			=> 'Product price cannot empty.',
						'product_price.array'				=> 'Product qty must be array.',
                        'submit.required' 					=> 'Step cannot empty.'
                    ]);
                    break;
				
                case 'step-10':
                    $validation = Validator::make($request->all(), [
						'pop_id'  				=> 'required',
                        'file'   				=> 'required|mimes:jpg,jpeg,png,pdf',
                        'date'    				=> 'required',
                        'supplier_name'    		=> 'required',
                        'supplier_warehouse' 	=> 'required'
                    ], [
						'pop_id.required'  				=> 'Purchase order cannot empty.',
                        'file.required'   				=> 'File cannot empty.',
                        'file.mimes'      				=> 'File must have an extension jpg, jpeg, png, and pdf',
                        'date.required'    				=> 'Date cannot empty.',
                        'supplier_name.required'    	=> 'Supplier name cannot empty.',
                        'supplier_warehouse.required' 	=> 'Supplier warehouse cannot empty.'
                    ]);
                    break;

                case 'step-11':
					$validation = Validator::make($request->all(), [
						'po_id'	  	=> 'required',
                        'file'   	=> 'required|mimes:jpg,jpeg,png,pdf',
						'status'    => 'required',
                        'date'    	=> 'required',
                        'bank'    	=> 'required',
                        'nominal' 	=> 'required'
                    ], [
						'po_id.required' 	=> 'PO cannot empty.',
                        'file.required'   	=> 'File cannot empty.',
                        'file.mimes'      	=> 'File must have an extension jpg, jpeg, png, and pdf.',
						'status.required' 	=> 'Type cannot empty.',
                        'date.required'    	=> 'Date cannot empty.',
                        'bank.required'    	=> 'Bank cannot empty.',
                        'nominal.required' 	=> 'Nominal cannot empty.'
                    ]);
                    break;

                case 'step-12':
                     $validation = Validator::make($request->all(), [
						'pr_id'					=> 'required',
                        'file'       			=> 'required|mimes:jpg,jpeg,png,pdf',
                        'start_date'  			=> 'required',
                        'finish_date'			=> 'required',
						'progress_production'	=> 'required',
                        'note'        			=> 'required'
                    ], [
						'pr_id.required'					=> 'Purchase cannot empty.',
                        'file.required'       				=> 'File cannot empty.',
                        'file.mimes'          				=> 'File must have an extension jpg, jpeg, png, and pdf.',
                        'start_date.required'  				=> 'Start date cannot empty.',
                        'finish_date.required' 				=> 'Finish date cannot empty.',
                        'note.required'        				=> 'Note cannot empty.',
						'progress_production.required'   	=> 'Progress production cannot empty.'
                    ]);
                    break;

                case 'step-13':
                    $validation = Validator::make($request->all(), [
						'por_id'  => 'required',
						'status'  => 'required',
                        'file'    => 'required|mimes:jpg,jpeg,png,pdf',
                        'date'    => 'required',
                        'bank'    => 'required',
                        'nominal_payment' => 'required'
                    ], [
						'por_id.required'  => 'Purchase cannot empty',
						'status.required'  => 'Type cannot empty',
                        'file.required'    => 'File cannot empty.',
                        'file.mimes'       => 'File must have an extension jpg, jpeg, png, and pdf.',
                        'date.required'    => 'Date cannot empty.',
                        'bank.required'    => 'Bank cannot empty.',
                        'nominal_payment.required' => 'Nominal cannot empty.'
                    ]);
                    break;
				case 'step-14':
					$validation = Validator::make($request->all(), [
						'pos_id'   		 => 'required',
						'shipment_code'	 => 'required',
                        'loading_date'   => 'required',
                        'departure_date' => 'required',
                        'from_port'      => 'required',
                        'to_port'        => 'required',
                        'eta'            => 'required',
						'product_id'	 => 'required|array',
						'product_unit'	 => 'required|array',
						'product_qty'	 => 'required|array'
                    ], [
						'pos_id.required'		  => 'Purchase cannot empty.',
						'shipment_code.required'  => 'Shipment code cannot empty.',
                        'loading_date.required'   => 'Loading date cannot empty.',
                        'departure_date.required' => 'Departure date cannot empty.',
                        'from_port.required'      => 'from port cannot empty.',
                        'to_port.required'        => 'to port cannot empty.',
                        'eta.required'            => 'ETA cannot empty.',
						'product_id.required'	  => 'Product cannot empty.',
						'product_id.array'		  => 'Product must be array.',
						'product_unit.required'	  => 'Product unit cannot empty.',
						'product_unit.array'	  => 'Product unit must be array.',
						'product_qty.required'	  => 'Product qty cannot empty.',
						'product_qty.array'		  => 'Product qty must be array.'
                    ]);
                    break;
				case 'step-15':
					$validation = Validator::make($request->all(), [
						'posw_id'   			=> 'required',
						'shipment_id'   		=> 'required',
                        'person'   				=> 'required',
                        'date_receive' 			=> 'required',
                        'warehouse_id'      	=> 'required',
                        'file'   				=> 'required|mimes:jpg,jpeg,png,pdf',
						'product_id'	 		=> 'required|array',
						'product_unit'	 		=> 'required|array',
						'product_qty'	 		=> 'required|array',
                    ], [
						'posw_id.required'		  		=> 'Purchase cannot empty.',
						'shipment_id.required'	  		=> 'Shipment cannot empty.',
                        'person.required'   	  		=> 'Person who is responsible cannot empty.',
                        'date_receive.required'   		=> 'Date received cannot empty.',
                        'warehouse_id.required'  		=> 'Warehouse cannot empty.',
                        'file.required'   		  		=> 'File cannot empty.',
                        'file.mimes'      		  		=> 'File must have an extension jpg, jpeg, png, and pdf.',
						'product_id.required'	  		=> 'Product cannot empty.',
						'product_id.array'		  		=> 'Product must be array.',
						'product_unit.required'	  		=> 'Product unit cannot empty.',
						'product_unit.array'	  		=> 'Product unit must be array.',
						'product_qty.required'	  		=> 'Product qty cannot empty.',
						'product_qty.array'		  		=> 'Product qty must be array.',
                    ]);
					
					break;
                case 'step-16':
                    $validation = Validator::make($request->all(), [
						'sod_id' 		=> 'required',
                        'receiver_name' => 'required',
                        'delivery_date' => 'required',
                        'email'         => 'required|email',
                        'phone'         => 'required|min:9|numeric',
                        'city_id2'       => 'required',
                        'address'       => 'required',
                        'warehousedeliver_id'  => 'required',
						'expedition_id'	=> 'required',
						'product_id'	=> 'required|array',
						'product_unit'	=> 'required|array',
						'product_qty'	=> 'required|array',
                    ], [
						'sod_id.required' 		 		=> 'Sales order cannot be empty.',
                        'receiver_name.required' 		=> 'Receiver name cannot be empty.',
                        'delivery_date.required' 		=> 'Delivery date cannot be empty.',
                        'email.required'         		=> 'Email cannot be empty.',
                        'email.email'            		=> 'Email not valid.',
                        'phone.required'         		=> 'Phone cannot be empty',
                        'phone.min'              		=> 'Phone must be at least 9 characters long',
                        'phone.numeric'          		=> 'Phone must be number',
                        'city_id2.required'       		=> 'Please select a city.',
                        'address.required'       		=> 'Address cannot be empty.',
                        'warehousedeliver_id.required'   		=> 'Please select a warehouse.',
						'expedition_id.required'   		=> 'Please select an expedition.',
						'product_id.required'	  		=> 'Product cannot empty.',
						'product_id.array'		  		=> 'Product must be array.',
						'product_unit.required'	  		=> 'Product unit cannot empty.',
						'product_unit.array'	  		=> 'Product unit must be array.',
						'product_qty.required'	  		=> 'Product qty cannot empty.',
						'product_qty.array'		  		=> 'Product qty must be array.',
                    ]);
                    break;
                
				case 'step-17':
					$validation = Validator::make($request->all(), [
						'sor_id'	   			=> 'required',
						'note'    				=> 'required',
                        'file'   				=> 'required|mimes:jpg,jpeg,png,pdf',
						'product_id'	 		=> 'required|array',
						'product_unit'	 		=> 'required|array',
						'product_qty'	 		=> 'required|array',
                    ], [
						'sor_id.required'		  		=> 'Sales cannot empty.',
                        'note.required'   				=> 'Note cannot empty.',
                        'file.required'   		  		=> 'File cannot empty.',
                        'file.mimes'      		  		=> 'File must have an extension jpg, jpeg, png, and pdf.',
						'product_id.required'	  		=> 'Product cannot empty.',
						'product_id.array'		  		=> 'Product must be array.',
						'product_unit.required'	  		=> 'Product unit cannot empty.',
						'product_unit.array'	  		=> 'Product unit must be array.',
						'product_qty.required'	  		=> 'Product qty cannot empty.',
						'product_qty.array'		  		=> 'Product qty must be array.',
                    ]);
					
					break;
				
                case 'step-18':
                    $validation = Validator::make($request->all(), [
						'sop_id'		 => 'required',
                        'file'         	 => 'required|mimes:jpg,jpeg,png,pdf',
                        'date_create'    => 'required',
                        'payment'        => 'required',
                        'payment_method' => 'required',
                        'nominal'        => 'required'
                    ], [
						'sop_id'				  => 'Sales cannot empty',
                        'file.required'           => 'File cannot empty.',
                        'file.mimes'              => 'File must have an extension jpg, jpeg, png, and pdf.',
						'date_create.required'    => 'Date invoice created cannot empty.',
                        'payment.required'        => 'Please select a payment.',
                        'payment_method.required' => 'Please select a payment method.',
                        'nominal.required'        => 'Nominal cannot empty.'
                    ]);
                    break;

                case 'step-19':
                    $validation = Validator::make($request->all(), [
						'date_trouble'				=> 'required',
                        'note_trouble'    			=> 'required'
                    ], [
						'date_trouble.required'    	=> 'Date trouble cannot empty.',
                        'note_trouble.required'     => 'Note cannot empty.'
                    ]);
                    break;
				case 'step-20':
					$validation = Validator::make($request->all(), [
                        'submit' => 'required'
                    ], [
                        'submit.required' => 'Step cannot empty.'
                    ]);
                    break;
            }

            if($validation->fails()) {
                return redirect('admin/report/project/details/progress/' . $id . '?' . $step . '=1#' . $step)
                    ->withErrors($validation)
                    ->withInput();
            } else {
                switch($step) {
                    case 'step-1':
                        $query->update([
                            'country_id'     => $request->country_id,
                            'city_id'        => $request->city_id,
                            'name'           => $request->name,
                            'customer_id'    => $request->customer_id,
                            'timeline'       => $request->timeline,
                            'constructor'    => $request->constructor,
                            'manager'        => $request->manager,
                            'consultant'     => $request->consultant,
                            'owner'          => $request->owner,
							'coa_id' 		 => $request->bank_id,
                            'payment_method' => $request->detail_payment,
                            'supply_method'  => $request->supply_method,
                            'ppn'            => $request->ppn
                        ]);

                        activity()
                            ->performedOn(new Project())
                            ->causedBy(session('bo_id'))
							->withProperties($query)
                            ->log('Change data project ' . $query->name . ' (Step 1)');
                        break;

                    case 'step-2':
                        $query->update([
                            'progress' => $query->progress < 15 ? 15 : $query->progress
                        ]);
						
						/* Project::find($query->id)->update([
							'delivery_cost'	=> str_replace(',','.',str_replace('.','',$request->delivery_cost)),
							'cutting_cost'	=> str_replace(',','.',str_replace('.','',$request->cutting_cost)),
							'misc_cost'	=> str_replace(',','.',str_replace('.','',$request->misc_cost))
						]); */
						
						ProjectProduct::where('project_id',$query->id)->whereNotIn('product_id',$request->product_id)->delete();
						
                        foreach($request->product_id as $key => $pi) {
                            $product = Product::find($pi);
                            $cogs    = 0;
                            
                            if($product->pricingPolicy) {
                                $cogs = $product->pricingPolicy->cogs;    
                            }
							
							$count = ProjectProduct::where('project_id',$query->id)->where('product_id',$pi)->count();
							
							if($count > 0){
								ProjectProduct::where(['project_id' => $query->id, 'product_id' => $pi])->update([
									'area'         => $request->product_area[$key],
									'spec'         => $request->product_spec[$key],
									'qty'          => $request->product_qty[$key],
									'cogs'         => $cogs,
									'price'        => $request->product_price[$key],
									'unit'         => $request->product_unit[$key]
								]);
							}else{
								ProjectProduct::create([
									'project_id'   => $query->id,
									'product_id'   => $pi,
									'area'   	   => $request->product_area[$key],
									'spec'   	   => $request->product_spec[$key],
									'qty'          => $request->product_qty[$key],
									'cogs'         => $cogs,
									'price'        => $request->product_price[$key],
									'unit'         => $request->product_unit[$key]
								]);
							}
                            
                        }

                        activity()
                            ->performedOn(new Project())
                            ->causedBy(session('bo_id'))
                            ->log('Change data project ' . $query->name . ' (Step 2)');
                        break;

                    case 'step-3':
                        $query->update([
                            'progress' => $query->progress < 20 ? 20 : $query->progress
                        ]);
						
						ProjectConsultantMeeting::whereNotIn('id',$request->consultant_id)->delete();
						
                        foreach($request->consultant_date as $key => $cd) {
							if($request->consultant_id[$key] !== '0'){
								
							}else{
								ProjectConsultantMeeting::create([
									'project_id' => $query->id,
									'date'       => $cd,
									'person'     => $request->consultant_person[$key],
									'result'     => $request->consultant_result[$key]
								]);
							}
                        }

                        activity()
                            ->performedOn(new Project())
                            ->causedBy(session('bo_id'))
                            ->log('Change data project ' . $query->name . ' (Step 3)');
                        break;

                    case 'step-4':
                        $query->update([
                            'progress' => $query->progress < 25 ? 25 : $query->progress
                        ]);
						
						$revision = ProjectQuotation::where('project_id', $query->id)->max('revision');
						$revision++;

						$projectQuotation = ProjectQuotation::create([
							'project_id'     => $query->id,
							'revision'  	 => $revision,
							'approved_by_1'  => 0,
							'approved_by_1'  => 0
						]);

                        foreach($request->project_product_id as $key => $ppi) {
                            ProjectProduct::find($ppi)->update([
                                'recommended_price' => str_replace(',','.',str_replace('.','',str_replace('IDR ','',$request->product_recommended_price[$key]))),
								'best_price' => str_replace(',','.',str_replace('.','',str_replace('IDR ','',$request->product_best_price[$key]))),
                                'discount' => str_replace(',','.',str_replace('.','',$request->product_discount[$key]))
                            ]);
							
							ProjectQuotationProduct::create([
								'project_quotation_id'     	=> $projectQuotation->id,
								'product_id'  	 			=> ProjectProduct::find($ppi)->product_id,
								'recommended_price'  		=> str_replace(',','.',str_replace('.','',str_replace('IDR ','',$request->product_recommended_price[$key]))),
								'best_price' 				=> str_replace(',','.',str_replace('.','',str_replace('IDR ','',$request->product_best_price[$key]))),
								'discount'					=> str_replace(',','.',str_replace('.','',$request->product_discount[$key]))
							]);
                        }

                        activity()
                            ->performedOn(new Project())
                            ->causedBy(session('bo_id'))
                            ->log('Change data project ' . $query->name . ' (Step 4)');
                        break;

                    case 'step-5':
                        $query->update([
                            'progress' => $query->progress < 30 ? 30 : $query->progress
                        ]);
						
						$projectSample = ProjectSample::create([
							'project_id' 	=> $query->id,
							'code' 		 	=> ProjectSample::generateCode(),
							'sent_date'		=> $request->sample_sent_date,
							'return_date'	=> $request->sample_return_date,
							'note'			=> $request->sample_note,
							'status'		=> '1',
							'approved_by_1' => 0,
							'approved_by_2' => 0
						]);
						
						foreach($request->sample_product_id as $key => $spi) {
							ProjectSampleProduct::create([
								'project_sample_id' => $projectSample->id,
								'product_id' 		=> $spi,
								'qty'        		=> $request->sample_qty[$key],
								'unit'       		=> $request->sample_unit[$key],
								'size'       		=> $request->sample_size[$key]
							]);
                        }

                        activity()
                            ->performedOn(new Project())
                            ->causedBy(session('bo_id'))
                            ->log('Change data project ' . $query->name . ' (Step 5)');
                        break;

                    case 'step-6':
                        $query->update([
                            'progress' => $query->progress < 35 ? 35 : $query->progress
                        ]);

                        ProjectNegotiation::whereNotIn('id',$request->negotiation_id)->delete();
						
                        foreach($request->negotiation_date as $key => $nd) {
							if($request->negotiation_id[$key] !== '0'){
								
							}else{
								ProjectNegotiation::create([
									'project_id' => $query->id,
									'date'       => $nd,
									'person'     => $request->negotiation_person[$key],
									'result'     => $request->negotiation_result[$key]
								]);
							}
                        }

                        activity()
                            ->performedOn(new Project())
                            ->causedBy(session('bo_id'))
                            ->log('Change data project ' . $query->name . ' (Step 6)');
                        break;

                    case 'step-7':
                        $query->update([
                            'progress' => $query->progress < 40 ? 40 : $query->progress
                        ]);
						
						$delivery_cost = floatval(str_replace(',','.',str_replace('.','',$request->delivery_cost)));
						$cutting_cost = floatval(str_replace(',','.',str_replace('.','',$request->cutting_cost)));
						$misc_cost = floatval(str_replace(',','.',str_replace('.','',$request->misc_cost)));
						
						$project = Project::find($query->id)->update([
							'delivery_cost'	=> $delivery_cost,
							'cutting_cost'	=> $cutting_cost,
							'misc_cost'	=> $misc_cost
						]);
						
						if($request->temp_so_id){
							
							$projectSale = ProjectSale::find($request->temp_so_id);
							
							if($request->has('file')) {
								if(Storage::exists($projectSale->so_file)) {
									Storage::delete($projectSale->so_file);
								}

								$image = $request->file('file')->store('public/project');
							} else {
								$image = $projectSale->so_file;
							}
							
							$projectSale->so_file = $image;
							$projectSale->sales_id = $request->sales_id;
							$projectSale->address = $request->sales_address;
							$projectSale->note = $request->sales_note;
							$projectSale->delivery_cost = $delivery_cost;
							$projectSale->cutting_cost = $cutting_cost;
							$projectSale->misc_cost	= $misc_cost;
							
							$projectSale->update();
							
							
							
						}else{
							$projectSale = ProjectSale::create([
								'user_id'		=> session('bo_id'),
								'project_id' 	=> $query->id,
								'sales_id'		=> $request->sales_id,
								'code' 		 	=> ProjectSale::generateCode(),
								'address'		=> $request->sales_address,
								'note'			=> $request->sales_note,
								'so_file'		=> $request->file('file') ? $request->file('file')->store('public/project') : '',
								'marketing_id' 	=> 0,
								'approved_id' 	=> 0,
								'delivery_cost'	=> $delivery_cost,
								'cutting_cost'	=> $cutting_cost,
								'misc_cost'		=> $misc_cost
							]);
						}
						
						ProjectSaleProduct::where('project_sale_id',$request->temp_so_id)->delete();
						
						$dataProduct = ProjectProduct::where('project_id', $query->id)->get();
						
						foreach($dataProduct as $ps) {
							ProjectSaleProduct::create([
								'project_sale_id'   => $projectSale->id,
								'product_id'   		=> $ps->product_id,
								'area'   	   		=> $ps->area,
								'spec'   	   		=> $ps->spec,
								'qty'          		=> $ps->qty,
								'cogs'        		=> $ps->cogs,
								'price'        		=> $ps->price,
								'recommended_price'	=> $ps->recommended_price,
								'best_price'		=> $ps->best_price,
								'discount'			=> $ps->discount,
								'unit'         		=> $ps->unit
							]);
                        }
						
						ProjectSaleShading::where('project_sale_id',$request->temp_so_id)->delete();
							
						foreach($request->shading_product_id as $key => $pi) {
							ProjectSaleShading::create([
								'project_sale_id'  => $request->temp_so_id,
								'product_id'   	   	=> $request->shading_product_id[$key],
								'warehouse_code'   	=> $request->shading_warehouse_code[$key],
								'stock_code'        => $request->shading_stock_code[$key],
								'code'         		=> $request->shading_code[$key],
								'qty'        		=> $request->shading_qty[$key]
							]);
						}

                        activity()
                            ->performedOn(new Project())
                            ->causedBy(session('bo_id'))
                            ->log('Change data project ' . $query->name . ' (Step 7)');
                        break;
					case 'step-8':
						$query->update([
                            'progress' => $query->progress < 40 ? 40 : $query->progress
                        ]);

                        ProjectPay::create([
							'user_id'			=> session('bo_id'),
                            'project_id'     	=> $query->id,
							'project_sale_id'	=> $request->sop_id,
							'code'				=> ProjectPay::generateCode(),
                            'image'          	=> $request->file('file')->store('public/project'),
                            'date'           	=> $request->date_create,
                            'due_date'          => $request->due_date,
							'nominal'        	=> str_replace(',','.',str_replace('.','',$request->nominal)),
                            'payment'        	=> $request->payment,
                            'payment_method' 	=> $request->payment_method,
							'note'				=> $request->note,
							'marketing_id'		=> 0,
							'approved_id'		=> 0
                        ]);

                        activity()
                            ->performedOn(new Project())
                            ->causedBy(session('bo_id'))
                            ->log('Change data project ' . $query->name . ' (Step 8)');
                        break;
						
                    case 'step-9':
                        $query->update([
                            'progress' => $query->progress < 43 ? 43 : $query->progress
                        ]);

						$projectpurchase = ProjectPurchase::create([
							'user_id'					=> session('bo_id'),
							'project_id' 				=> $query->id,
							'project_sale_id' 			=> $request->so_id,
							'code'						=> ProjectPurchase::generateCode(),
							'note' 		 				=> $request->sales_note,
							'supplier_id'				=> $request->supplier_id,
							'production_lead_time'		=> $request->sales_note,
							'estimated_delivery'		=> $request->est_delivery_date,
							'estimated_arrival' 		=> $request->est_arrival_date,
							'factory_name' 				=> $request->factory_name,
							'customer_id'				=> $request->customer_id,
							'sales_id'					=> $request->sales_po,
							'on_behalf'					=> $request->on_behalf,
							'delivery_address'			=> $request->delivery_address,
							'country_id' 				=> $request->country_id,
							'city_id' 					=> $request->city_id,
							'courier_method'			=> $request->courier_method,
							'pic'						=> $request->pic_name,
							'pic_no'					=> $request->pic_number,
							'payment_method'			=> $request->payment_method,
							'price'						=> $request->price,
							'currency_id'				=> $request->currency,
							'brand_on_box'				=> $request->brand,
							'sni'						=> $request->sni,
							'checked_by'				=> 0,
							'approved_by'				=> 0
						]);
						
						foreach($request->product_id as $key => $pi) {
							ProjectPurchaseProduct::create([
								'project_purchase_id' 	=> $projectpurchase->id,
								'product_id'  	 		=> $request->product_id[$key],
								'qty'  					=> $request->product_qty[$key],
								'unit'					=> $request->product_unit[$key],
								'price'					=> str_replace(',','.',str_replace('.','',str_replace('IDR ','',$request->product_price[$key]))),
								'remark'				=> $request->product_remark[$key]
							]);
                        }
							
                        activity()
                            ->performedOn(new Project())
                            ->causedBy(session('bo_id'))
                            ->log('Change data project ' . $query->name . ' (Step 9)');
                        break;
					
					case 'step-10':
                        $query->update([
                            'progress' => $query->progress < 45 ? 45 : $query->progress
                        ]);

                        ProjectProforma::create([
							'project_id' 			=> $query->id,
                            'project_purchase_id' 	=> $request->pop_id,
                            'image'      			=> $request->file('file') ? $request->file('file')->store('public/project') : '',
                            'date'       			=> $request->date,
                            'supplier_name'    		=> $request->supplier_name,
                            'supplier_warehouse'    => $request->supplier_warehouse
                        ]);

                        activity()
                            ->performedOn(new Project())
                            ->causedBy(session('bo_id'))
                            ->log('Change data project ' . $query->name . ' (Step 10)');
                        break;
					
                    case 'step-11':
                        $query->update([
                            'progress' => $query->progress < 48 ? 48 : $query->progress
                        ]);

                        ProjectPayment::create([
                            'project_id' 			=> $query->id,
							'project_purchase_id' 	=> $request->po_id,
                            'image'      			=> $request->file('file')->store('public/project'),
                            'date'       			=> $request->date,
                            'nominal'    			=> str_replace(',','.',str_replace('.','',$request->nominal)),
                            'bank'       			=> $request->bank,
                            'status'     			=> $request->status
                        ]);

                        activity()
                            ->performedOn(new Project())
                            ->causedBy(session('bo_id'))
                            ->log('Change data project ' . $query->name . ' (Step 11)');
                        break;

                    case 'step-12':
                        $query->update([
                            'progress' => $query->progress < 50 ? 50 : $query->progress
                        ]);

                        ProjectProduction::create([
                            'project_id'  			=> $query->id,
							'project_purchase_id'	=> $request->pr_id,
                            'image'       			=> $request->file('file') ? $request->file('file')->store('public/project') : '',
                            'start_date'  			=> $request->start_date,
                            'finish_date' 			=> $request->finish_date,
                            'note'        			=> $request->note,
							'progress'	  			=> $request->progress_production
                        ]);

                        activity()
                            ->performedOn(new Project())
                            ->causedBy(session('bo_id'))
                            ->log('Change data project ' . $query->name . ' (Step 12)');
                        break;

                    case 'step-13':
                        $query->update([
                            'progress' => $query->progress < 55 ? 55 : $query->progress
                        ]);

                        ProjectPayment::create([
                            'project_id' 				=> $query->id,
							'project_purchase_id'		=> $request->por_id,
                            'image'      				=> $request->file('file')->store('public/project'),
                            'date'       				=> $request->date,
                            'nominal'    				=> str_replace(',','.',str_replace('.','',$request->nominal_payment)),
                            'bank'       				=> $request->bank,
                            'status'     				=> $request->status
                        ]);

                        activity()
                            ->performedOn(new Project())
                            ->causedBy(session('bo_id'))
                            ->log('Change data project ' . $query->name . ' (Step 13)');
                        break;

                    case 'step-14':
                        $query->update([
                            'progress' => $query->progress < 60 ? 60 : $query->progress
                        ]);

                        $projectshipment = ProjectShipment::create([
                            'project_id'     		=> $query->id,
							'project_purchase_id'	=> $request->pos_id,
							'shipment_code'			=> $request->shipment_code,
                            'loading_date'   		=> $request->loading_date,
                            'departure_date' 		=> $request->departure_date,
                            'from_port'      		=> $request->from_port,
                            'to_port'        		=> $request->to_port,
                            'eta'            		=> $request->eta,
                            'note'           		=> $request->note
                        ]);
						
						foreach($request->product_id as $key => $pi) {
							ProjectShipmentProduct::create([
								'project_shipment_id' 	=> $projectshipment->id,
								'product_id'  	 		=> $request->product_id[$key],
								'qty'  					=> $request->product_qty[$key],
								'unit'					=> $request->product_unit[$key]
							]);
                        }

                        activity()
                            ->performedOn(new Project())
                            ->causedBy(session('bo_id'))
                            ->log('Change data project ' . $query->name . ' (Step 14)');
                        break;
					
					case 'step-15':
					
						$query->update([
                            'progress' => $query->progress < 65 ? 65 : $query->progress
                        ]);

                        $projectwarehouse = ProjectWarehouse::create([
							'user_id'				=> session('bo_id'),
                            'project_id'     		=> $query->id,
							'project_purchase_id'	=> $request->posw_id,
							'project_shipment_id'	=> $request->shipment_id,
							'code'					=> ProjectWarehouse::generateCode(),
                            'image'      	 		=> $request->file('file')->store('public/project'),
                            'date_receive' 	 		=> $request->date_receive,
                            'warehouse_id'  	 	=> $request->warehouse_id,
                            'person'         		=> $request->person
                        ]);
						
						foreach($request->product_id as $key => $pi) {
							ProjectWarehouseProduct::create([
								'project_warehouse_id' 	=> $projectwarehouse->id,
								'product_id'  	 		=> $request->product_id[$key],
								'qty'  					=> $request->product_qty[$key],
								'unit'					=> $request->product_unit[$key],
								'qty_broken'			=> $request->product_qty_broken[$key],
								'unit_broken'			=> $request->product_unit[$key],
							]);
                        }

                        activity()
                            ->performedOn(new Project())
                            ->causedBy(session('bo_id'))
                            ->log('Change data project ' . $query->name . ' (Step 15)');
						break;
						
					case 'step-16':
						
						$query->update([
                            'progress' => $query->progress < 80 ? 80 : $query->progress
                        ]);

                        $projectdelivery = ProjectDelivery::create([
							'user_id'				=> session('bo_id'),
                            'project_id'    		=> $query->id,
							'project_sale_id'    	=> $request->sod_id,
							'code'					=> ProjectDelivery::generateCode(),
                            'city_id'       		=> $request->city_id2,
							'receiver_name' 		=> $request->receiver_name,
                            'delivery_date' 		=> $request->delivery_date,
                            'email'         		=> $request->email,
                            'phone'        			=> $request->phone,
							'is_dropshipper'        => $request->dropshipper,
							'dropshipper_id'        => $request->dropshipper_id ? $request->dropshipper_id : 0,
                            'address'       		=> $request->address,
                            'warehouse_id'         	=> $request->warehousedeliver_id,
							'vendor_id'				=> $request->expedition_id,
							'approved_by'			=> 0,
							'image'       			=> $request->file('file') ? $request->file('file')->store('public/project') : ''
                        ]);
						
						foreach($request->product_id as $key => $pi) {
							ProjectDeliveryProduct::create([
								'project_delivery_id' 			=> $projectdelivery->id,
								'product_id'  	 				=> $request->product_id[$key],
								'qty'  							=> $request->product_qty[$key],
								'unit'							=> $request->product_unit[$key]
							]);
                        }

                        activity()
                            ->performedOn(new Project())
                            ->causedBy(session('bo_id'))
                            ->log('Change data project ' . $query->name . ' (Step 16)');
                        break;
					
                    case 'step-17':
                        $query->update([
                            'progress' => $query->progress < 80 ? 80 : $query->progress
                        ]);

                        $projectreturn = ProjectSaleReturn::create([
							'user_id'				=> session('bo_id'),
                            'project_id'     		=> $query->id,
							'project_sale_id'		=> $request->sor_id,
							'code'					=> ProjectSaleReturn::generateCode(),
                            'image'      	 		=> $request->file('file')->store('public/project'),
                            'note'         			=> $request->note,
							'approved_by'			=> 0
                        ]);
						
						foreach($request->product_id as $key => $pi) {
							ProjectSaleReturnProduct::create([
								'project_sale_return_id' 		=> $projectreturn->id,
								'product_id'  	 				=> $request->product_id[$key],
								'qty'  							=> $request->product_qty[$key],
								'unit'							=> $request->product_unit[$key]
							]);
                        }
						
						#start notif
						$role = array('1','2','3','4','5','6','7','9','10','11');
						$title = 'Project has been updated!';
						$description = 'Project '.$query->code.' with details sales code '.$projectreturn->projectSale->code.' has been returned with sales return code '.$projectreturn->code.'.';
						$link = '#';
						Notification::sendNotif($role,$title,$description,$link);
						#end notif
						
                        activity()
                            ->performedOn(new Project())
                            ->causedBy(session('bo_id'))
                            ->log('Change data project ' . $query->name . ' (Step 17)');
					
						break;
					
					case 'step-18':
						$query->update([
                            'progress' => $query->progress < 85 ? 85 : $query->progress
                        ]);

                        ProjectPay::create([
							'user_id'			=> session('bo_id'),
                            'project_id'     	=> $query->id,
							'project_sale_id'	=> $request->sop_id,
							'code'				=> ProjectPay::generateCode(),
                            'image'          	=> $request->file('file')->store('public/project'),
                            'date'           	=> $request->date_create,
                            'due_date'          => $request->due_date,
							'nominal'        	=> str_replace(',','.',str_replace('.','',$request->nominal)),
                            'payment'        	=> $request->payment,
                            'payment_method' 	=> $request->payment_method,
							'note'				=> $request->note,
							'marketing_id'		=> 0,
							'approved_id'		=> 0
                        ]);
						
						#start notif
						$role = array('1','2','3','4','5','6','7','9','10','11');
						$title = 'Project has been updated!';
						$description = 'Project '.$query->code.' with details payment sales code '.$pp->code.' and invoice '.$projectpay->code.' has been updated by '.session('bo_name').' in Full Payment Sales Form.';
						$link = '#';
						Notification::sendNotif($role,$title,$description,$link);
						#end notif
						
                        activity()
                            ->performedOn(new Project())
                            ->causedBy(session('bo_id'))
                            ->log('Change data project ' . $query->name . ' (Step 18)');
                        break;
					
                    case 'step-19':
					
						/*return redirect('admin/'.$uri[2].'/project/progress/' . $id . '?step-19=1#step-19')
										->withErrors($query->getBalance()); */
					
                        $query->update([
                            'progress' => $query->progress < 90 ? 90 : $query->progress
                        ]);
						
						ProjectTroubleshooting::create([
							'user_id'			=> session('bo_id'),
                            'project_id'     	=> $query->id,
                            'image'          	=> $request->file('file') ? $request->file('file')->store('public/project') : '',
                            'date_trouble'     	=> $request->date_trouble,
							'note'				=> $request->note_trouble
                        ]);
						
						#start notif
						$role = array('1');
						$title = 'Project has been updated!';
						$description = 'Project '.$query->code.' with details troubleshooting information has been updated by '.session('bo_name');
						$link = '#';
						Notification::sendNotif($role,$title,$description,$link);
						#end notif

                        activity()
                            ->performedOn(new Project())
                            ->causedBy(session('bo_id'))
                            ->log('Add troubleshooting to project ' . $query->name . ' (Step 19)');
                        break;
						
                    case 'step-20':
                        $query->update([
                            'progress' => $query->progress < 100 ? 100 : $query->progress
                        ]);
						
						#start notif
						$role = array('1','2','3','4','5','6','7','9','10','11');
						$title = 'Project has been updated!';
						$description = 'Project '.$query->code.' has been closed and approved successfully by '.session("bo_name");
						$link = '#';
						Notification::sendNotif($role,$title,$description,$link);
						#end notif
						
						$user = UserRole::whereIn('role',$role)->groupBy('user_id')->get();

						foreach($user as $u){
							if($u->user->status == '1'){
								$payload = [
									'email'   	=> $u->user->email,
									'name'    	=> $u->user->name,
									'closed_by' => session('bo_name'),
									'project'  	=> $query,
									'view'    	=> 'project_closing',
									'subject' 	=> 'SMB | Project ' . $query->code
								];
								
								dispatch(new EmailProcess($payload));
							}
						}
						
						Approval::where('approvalable_type', 'projects')
							->where('approvalable_id', $query->id)
							->update([
								'seen' => 1,
								'approved_by' => session('bo_id')
							]);
						
                        activity()
                            ->performedOn(new Project())
                            ->causedBy(session('bo_id'))
                            ->log('Change data project ' . $query->name . ' (Step 20)');
							
                        break;
                }
				
				if(explode('-',$step)[1] < 20){
					$nextangka = explode('-',$step)[1] + 1;
				}
				
                return redirect('admin/report/project/details/progress/' . $id . '?step-' . (isset($nextangka) ? $nextangka : 20) . '=1#step-' . (isset($nextangka) ? $nextangka : 20))
                    ->with(['success' => 'Data successfully saved.']);
            }
        } else {

			$data = [
				'title'   		=> 'Progress Data Project',
				'vendor' 		=> Vendor::where('status', 1)->get(),
				'country' 		=> Country::where('status', 1)->get(),
				'city'    		=> City::all(),
				'project' 		=> $query,
				'bank' 			=> Coa::where('parent_id', 8)->where('status', 1)->get(),
				'dropshipper' 	=> Dropshipper::where('status', 1)->get(),
				'content'		=> 'admin.report.project.project_progress'
			];

            return view('admin.layouts.index', ['data' => $data]);
        }
    }
	
	public function print($param, $id)
    {
        if($param == 'quotation_order'){
			$project_id = base64_decode($id);
			$project    = Project::find($project_id);

			if(!$project) {
				abort(404);
			}
			
			$pdf = PDF::loadView('admin.pdf.project.' . $param, [
					'project' => $project,
					'brand'   => Brand::whereIn('code', ['TR', 'FI', 'SM', 'BT', 'BV', 'GTI'])->get()
				],
				[],
				[ 
				  'format' => 'A4-P',
				  'orientation' => 'P'
				]
			);
		}else if($param == 'warehouse_receive'){
			$warehouse_id = base64_decode($id);
			$project = ProjectWarehouse::find($warehouse_id);
			
			if(!$warehouse_id) {
				abort(404);
			}
			
			$pdf = PDF::loadView('admin.pdf.project.' . $param, [
					'project' => $project,
					'brand'   => Brand::whereIn('code', ['TR', 'FI', 'SM', 'BT', 'BV', 'GTI'])->get()
				],
				[],
				[ 
				  'format' => 'A4-P',
				  'orientation' => 'P'
				]
			);
			
		}else if($param == 'negotiation_order'){
			$project_id = base64_decode($id);
			$project    = Project::find($project_id);

			if(!$project) {
				abort(404);
			}
			
			$pdf = PDF::loadView('admin.pdf.project.' . $param, [
					'project' => $project,
					'brand'   => Brand::whereIn('code', ['TR', 'FI', 'SM', 'BT', 'BV', 'GTI'])->get()
				],
				[],
				[ 
				  'format' => 'A3-L',
				  'orientation' => 'L'
				]
			);
		}else if($param == 'sample_order'){
			$project_id = base64_decode($id);
			$project    = ProjectSample::find($project_id);

			if(!$project) {
				abort(404);
			}
			
			$pdf = PDF::loadView('admin.pdf.project.' . $param, [
					'project' => $project,
					'brand'   => Brand::whereIn('code', ['TR', 'FI', 'SM', 'BT', 'BV', 'GTI'])->get()
				],
				[],
				[ 
				  'format' => 'A4-P',
				  'orientation' => 'P'
				]
			);
		}else if($param == 'purchase_order'){
			$project_id = base64_decode($id);
			$project    = ProjectPurchase::find($project_id);

			if(!$project) {
				abort(404);
			}
			
			$pdf = PDF::loadView('admin.pdf.project.' . $param, [
					'project' => $project,
					'brand'   => Brand::whereIn('code', ['TR', 'FI', 'SM', 'BT', 'BV', 'GTI'])->get()
				],
				[],
				[ 
				  'format' => 'A4-P',
				  'orientation' => 'P'
				]
			);
		}else if($param == 'delivery_order'){
			$do_id = base64_decode($id);
			$project = ProjectDelivery::find($do_id);
			
			if(!$do_id) {
				abort(404);
			}
			
			$pdf = PDF::loadView('admin.pdf.project.' . $param, [
					'project' => $project,
					'brand'   => Brand::whereIn('code', ['TR', 'FI', 'SM', 'BT', 'BV', 'GTI'])->get()
				],
				[],
				[ 
				  'format' => 'A4-P',
				  'orientation' => 'P'
				]
			);
			
		}else if($param == 'sales_return'){
			$project_id = base64_decode($id);
			$project    = ProjectSaleReturn::find($project_id);

			if(!$project) {
				abort(404);
			}
			
			$pdf = PDF::loadView('admin.pdf.project.' . $param, [
					'project' => $project,
					'brand'   => Brand::whereIn('code', ['TR', 'FI', 'SM', 'BT', 'BV', 'GTI'])->get()
				],
				[],
				[ 
				  'format' => 'A4-P',
				  'orientation' => 'P'
				]
			);
		}else if($param == 'purchase_return'){
			$project_id = base64_decode($id);
			$project    = ProjectPurchaseReturn::find($project_id);

			if(!$project) {
				abort(404);
			}
			
			$pdf = PDF::loadView('admin.pdf.project.' . $param, [
					'project' => $project,
					'brand'   => Brand::whereIn('code', ['TR', 'FI', 'SM', 'BT', 'BV', 'GTI'])->get()
				],
				[],
				[ 
				  'format' => 'A4-P',
				  'orientation' => 'P'
				]
			);
		}else if($param == 'sales_invoice'){
			$project_id = base64_decode($id);
			$project    = ProjectPay::find($project_id);

			if(!$project) {
				abort(404);
			}
			
			$pdf = PDF::loadView('admin.pdf.project.' . $param, [
					'project' => $project,
					'brand'   => Brand::whereIn('code', ['TR', 'FI', 'SM', 'BT', 'BV', 'GTI'])->get()
				],
				[],
				[ 
				  'format' => 'A4-P',
				  'orientation' => 'P'
				]
			);
			
		}else if($param == 'sales_order'){
			$project_id = base64_decode($id);
			$project    = ProjectSale::find($project_id);

			if(!$project) {
				abort(404);
			}
			
			$pdf = PDF::loadView('admin.pdf.project.' . $param, [
					'project' => $project,
					'brand'   => Brand::whereIn('code', ['TR', 'FI', 'SM', 'BT', 'BV', 'GTI'])->get()
				],
				[],
				[ 
				  'format' => 'A4-P',
				  'orientation' => 'P'
				]
			);
		}else{
			$project_id = base64_decode($id);
			$project    = Project::find($project_id);

			if(!$project) {
				abort(404);
			}
			
			$pdf = PDF::loadView('admin.pdf.project.' . $param, [
				'project' => $project,
				'brand'   => Brand::whereIn('code', ['TR', 'FI', 'SM', 'BT', 'BV', 'GTI'])->get()
			]);
		}

        return $pdf->stream('Invoice Project ' . str_replace('/', '-', $project->code) . '.pdf');
    }
	
	public function printHtml($param, $id)
    {
        if($param == 'quotation_order'){
			$project_id = base64_decode($id);
			$project    = ProjectQuotation::find($project_id);
			$detail = ProjectQuotationProduct::where('project_quotation_id',$project_id)->orderBy('id')->get();

			if(!$project) {
				abort(404);
			}
		
		}else if($param == 'warehouse_receive'){
			$warehouse_id = base64_decode($id);
			$project = ProjectWarehouse::find($warehouse_id);
			
			if(!$warehouse_id) {
				abort(404);
			}
			
		}else if($param == 'negotiation_order'){
			$project_id = base64_decode($id);
			$project    = Project::find($project_id);

			if(!$project) {
				abort(404);
			}
			
		}else if($param == 'sample_order'){
			$project_id = base64_decode($id);
			$project    = ProjectSample::find($project_id);

			if(!$project) {
				abort(404);
			}
			
		
		}else if($param == 'purchase_order'){
			$project_id = base64_decode($id);
			$project    = ProjectPurchase::find($project_id);

			if(!$project) {
				abort(404);
			}
		
		}else if($param == 'delivery_order'){
			$do_id = base64_decode($id);
			$project = ProjectDelivery::find($do_id);
			
			if(!$do_id) {
				abort(404);
			}
			
			
		
		}else if($param == 'sales_proforma'){
			$do_id = base64_decode($id);
			$project = ProjectDelivery::find($do_id);
			
			if(!$do_id) {
				abort(404);
			}
			
			
		}else if($param == 'sales_proforma_other'){
			$do_id = base64_decode($id);
			$project = ProjectDelivery::find($do_id);
			
			if(!$do_id) {
				abort(404);
			}
			
			
		}else if($param == 'sales_return'){
			$project_id = base64_decode($id);
			$project    = ProjectSaleReturn::find($project_id);

			if(!$project) {
				abort(404);
			}
			
			
		}else if($param == 'purchase_return'){
			$project_id = base64_decode($id);
			$project    = ProjectPurchaseReturn::find($project_id);

			if(!$project) {
				abort(404);
			}
			
			
		}else if($param == 'sales_invoice'){
			$project_id = base64_decode($id);
			$project    = ProjectPay::find($project_id);

			if(!$project) {
				abort(404);
			}
			
		
			
		}else if($param == 'sales_invoice_other'){
			$project_id = base64_decode($id);
			$project    = ProjectPay::find($project_id);

			if(!$project) {
				abort(404);
			}
			
			
			
		}else if($param == 'sales_order'){
			$project_id = base64_decode($id);
			$project    = ProjectSale::find($project_id);

			if(!$project) {
				abort(404);
			}
			
			
		}else if($param == 'sales_bill'){
			$project_id = base64_decode($id);
			$project    = ProjectBill::find($project_id);

			if(!$project) {
				abort(404);
			}
			
			
		}else if($param == 'sales_cost'){
			$project_id = base64_decode($id);
			$project    = ProjectSale::find($project_id);

			if(!$project) {
				abort(404);
			}
			
		}else if($param == 'sales_news'){
			$project_id = base64_decode($id);
			$project    = ProjectSale::find($project_id);

			if(!$project) {
				abort(404);
			}
			
		}else{
			$project_id = base64_decode($id);
			$project    = Project::find($project_id);

			if(!$project) {
				abort(404);
			}

		}

        return view('admin.pdf.project.'.$param, [
			'project' 	=> $project,
			'detail'	=> isset($detail) ? $detail : ''
		]);
    }
	
	public function approval(Request $request)
    {
		$pqid = $request->id;
		$approvalke = $request->approvalKe;
		$mode = $request->mode;
		
		if($mode == 'quotation'){
			$pq = ProjectQuotation::find($pqid);
			if($approvalke == 1){
				$pq->approved_by_1 = session('bo_id');
			}else if($approvalke == 2){
				$pq->approved_by_2 = session('bo_id');
			}
			$pq->save();
			
			#start notif
			$role = array('1','2','3','4','5','6','7','9','10','11');
			$title = 'Project has been updated!';
			$description = 'Project '.$pq->project->code.' quotation revision '.$pq->revision.' has been approved '.$approvalke.' by '.session('bo_name');
			$link = '#';
			Notification::sendNotif($role,$title,$description,$link);
			#end notif
			
			activity()
                    ->performedOn(new ProjectSample())
                    ->causedBy(session('bo_id'))
                    ->withProperties($pq)
                    ->log('Add approval to project quotation');
			
			$response = [
				'status'  => 200,
				'message' => 'Data added successfully.'
			];

			return response()->json($response);
		}elseif($mode == 'sales_return'){
			$pq = ProjectSaleReturn::find($pqid);
			if($approvalke == 1){
				$pq->approved_by = session('bo_id');
			}
			
			$pq->save();
			
			activity()
                    ->performedOn(new ProjectSaleReturn())
                    ->causedBy(session('bo_id'))
                    ->withProperties($pq)
                    ->log('Add approval to project sale return');
					
			$response = [
				'status'  => 200,
				'message' => 'Data added successfully.'
			];

			return response()->json($response);
		}elseif($mode == 'sales_invoice'){
			$pq = ProjectPay::find($pqid);
			if($approvalke == 1){
				$pq->marketing_id = session('bo_id');
			}elseif($approvalke == 2){
				$pq->approved_id = session('bo_id');
			}
			
			$pq->save();
			
			#start notif
			$role = array('1','2','3','4','5','6','7','9','10','11');
			$title = 'Project has been updated!';
			$description = 'Project '.$pq->projectSale->project->code.' with details sales code '.$pq->projectSale->code.' has been approved by '.session('bo_name');
			$link = '#';
			Notification::sendNotif($role,$title,$description,$link);
			#end notif
			
			activity()
                    ->performedOn(new ProjectPay())
                    ->causedBy(session('bo_id'))
                    ->withProperties($pq)
                    ->log('Add approval to project sales invoice');
					
			$response = [
				'status'  => 200,
				'message' => 'Data added successfully.'
			];

			return response()->json($response);
		}elseif($mode == 'purchase_return'){
			$pq = ProjectPurchaseReturn::find($pqid);
			if($approvalke == 1){
				$pq->approved_by = session('bo_id');
			}
			
			$pq->save();
			
			activity()
                    ->performedOn(new ProjectPurchaseReturn())
                    ->causedBy(session('bo_id'))
                    ->withProperties($pq)
                    ->log('Add approval to project purchase return');
					
			$response = [
				'status'  => 200,
				'message' => 'Data added successfully.'
			];

			return response()->json($response);
		}elseif($mode == 'delivery'){
			$pq = ProjectDelivery::find($pqid);
			if($approvalke == 1){
				$pq->approved_by = session('bo_id');
			}
			
			$pq->save();
			
			#start notif
			$role = array('1','2','3','4','5','6','7','9','10','11');
			$title = 'Project has been updated!';
			$description = 'Project '.$pq->project->code.' with details delivery order code '.$pq->code.' has been approved by '.session("bo_name");
			$link = '#';
			Notification::sendNotif($role,$title,$description,$link);
			#end notif
			
			activity()
                    ->performedOn(new ProjectDelivery())
                    ->causedBy(session('bo_id'))
                    ->withProperties($pq)
                    ->log('Add approval to project delivery');
					
			$response = [
				'status'  => 200,
				'message' => 'Data added successfully.'
			];

			return response()->json($response);
		}elseif($mode == 'sample'){
			$pq = ProjectSample::find($pqid);
			if($approvalke == 1){
				$pq->approved_by_1 = session('bo_id');
			}else if($approvalke == 2){
				$pq->approved_by_2 = session('bo_id');
			}
			$pq->save();
			
			#start notif
			$role = array('1','2','3','4','5','6','7','9','10','11');
			$title = 'Project has been updated!';
			$description = 'Project '.$pq->project->code.' with details sample code '.$pq->code.' has been approved by '.session("bo_name");
			$link = '#';
			Notification::sendNotif($role,$title,$description,$link);
			#end notif
			
			activity()
                    ->performedOn(new ProjectSample())
                    ->causedBy(session('bo_id'))
                    ->withProperties($pq)
                    ->log('Add approval to project sample');
			
			$response = [
				'status'  => 200,
				'message' => 'Data added successfully.'
			];

			return response()->json($response);
		}elseif($mode == 'sale'){
			$ps = ProjectSale::find($pqid);
			if($approvalke == 1){
				$ps->marketing_id = session('bo_id');
			}else if($approvalke == 2){
				$ps->approved_id = session('bo_id');
			}
			$ps->save();
			
			#start notif
			$role = array('1','2','3','4','5','6','7','9','10','11');
			$title = 'Project has been updated!';
			$description = 'Project '.$ps->project->code.' with details sales code '.$ps->code.' has been approved '.$approvalke.' by '.session("bo_name");
			$link = '#';
			Notification::sendNotif($role,$title,$description,$link);
			#end notif
			
			activity()
                    ->performedOn(new ProjectSale())
                    ->causedBy(session('bo_id'))
                    ->withProperties($ps)
                    ->log('Add approval to project sale');
			
			$response = [
				'status'  => 200,
				'message' => 'Data added successfully.'
			];

			return response()->json($response);
		}elseif($mode == 'purchase'){
			$ps = ProjectPurchase::find($pqid);
			if($approvalke == 1){
				$ps->checked_by = session('bo_id');
			}else if($approvalke == 2){
				$ps->approved_by = session('bo_id');
			}
			$ps->save();
			
			#start notif
			$role = array('1','2','3','4','5','6','7','9','10','11');
			$title = 'Project has been updated!';
			$description = 'Project '.$ps->project->code.' with details purchase code '.$ps->code.' has been approved '.$approvalke.' by '.session("bo_name");
			$link = '#';
			Notification::sendNotif($role,$title,$description,$link);
			#end notif
			
			activity()
                    ->performedOn(new ProjectPurchase())
                    ->causedBy(session('bo_id'))
                    ->withProperties($ps)
                    ->log('Add approval to project purchase');
			
			$response = [
				'status'  => 200,
				'message' => 'Data added successfully.'
			];

			return response()->json($response);
		}
	}
	
	public function getSalesProduct(Request $request)
    {
        $projectsale = ProjectSaleProduct::where('project_sale_id',$request->idprojectsale)->get();
		$project = ProjectSale::find($request->idprojectsale);
		
		$data = [];
		
        foreach($projectsale as $ps){
			$m2 = (( $ps->product->type->length * $ps->product->type->width ) / 10000) * $ps->product->carton_pcs;
			$countbox = ceil($ps->qty / $m2);
			
			$projectpurchase = ProjectPurchase::where('project_sale_id',$request->idprojectsale)->get();
			
			$tot = 0;
			foreach($projectpurchase as $pp){
				foreach($pp->projectPurchaseProduct as $ppd){
					if($ppd->product_id == $ps->product_id){
						$tot += $ppd->qty;
					}
				}
			}
			
			$projectdelivery = ProjectDelivery::where('project_sale_id',$request->idprojectsale)->get();
			
			$totdelivery = 0;
			foreach($projectdelivery as $pp){
				foreach($pp->projectDeliveryProduct as $ppd){
					if($ppd->product_id == $ps->product_id){
						$totdelivery += $ppd->qty;
					}
				}
			}
			
			if($ps->product->type->category->parent()->parent()->slug == 'tile'){
				$data[] = [
					'product_id'		=> $ps->product_id,
					'product_name'		=> $ps->product->name(),
					'product_code'		=> $ps->product->type->code,
					'qty'          		=> $countbox,
					'qty_left'			=> $countbox - $tot,
					'unit'          	=> 'Box',
					'unitraw'          	=> $ps->unit,
					'unitconvert'		=> $ps->unit == 3 ? 2 : $ps->unit,
					'm2'				=> $m2,
					'countbox'			=> $countbox,
					'qty_left_deliver'	=> $countbox - $totdelivery
				];
			}else{
				$data[] = [
					'product_id'		=> $ps->product_id,
					'product_name'		=> $ps->product->name(),
					'product_code'		=> $ps->product->type->code,
					'qty'          		=> $ps->qty,
					'qty_left'			=> $ps->qty - $tot,
					'unit'          	=> $ps->unit(),
					'unitraw'          	=> $ps->unit,
					'unitconvert'		=> $ps->unit,
					'm2'				=> 0,
					'countbox'			=> 0,
					'qty_left_deliver'	=> $ps->qty - $totdelivery,
				];
			}
        }

        return response()->json($data);
    }
	
	public function getPurchaseProduct(Request $request)
    {
        $projectpurchase = ProjectPurchaseProduct::where('project_purchase_id',$request->idpo)->get();
		
		$data = [];
		
		foreach($projectpurchase as $pp){
			$m2 = (( $pp->product->type->length * $pp->product->type->width ) / 10000) * $pp->product->carton_pcs;
			
			$projectshipment = ProjectShipment::where('project_purchase_id',$request->idpo)->get();
			
			$tot = 0;
			foreach($projectshipment as $ps){
				foreach($ps->projectShipmentProduct as $psp){
					if($psp->product_id == $pp->product_id){
						$tot += $psp->qty;
					}
				}
			}
			
			$data[] = [
				'product_id'	=> $pp->product_id,
				'product_name'	=> $pp->product->name(),
				'qty'			=> $pp->qty,
				'unit'			=> $pp->unit == 3 ? 'Box' : $pp->unit(),
				'convertunit'	=> $pp->unit == 3 ? 2 : $pp->unit,
				'qty_left'		=> $pp->qty - $tot,
				'qty_sent'		=> $tot,
				'm2'			=> $pp->m2,
				'fixunit'		=> round($pp->qty * $m2,2)
			];
		}
		
		return response()->json($data);
	}
	
	public function getPurchaseInfo(Request $request)
	{
		$projectpurchase = ProjectPurchaseProduct::where('project_purchase_id',$request->purchaseid)->get();
		$project = ProjectPurchase::find($request->purchaseid);
		$projectproduction = ProjectProduction::where('project_purchase_id',$request->purchaseid)->get();
		
		$total = 0;
		$totalpaid = 0;
		$progressleft = 100;
		$progresstotal = 0;
		$datapayment = [];
		
		foreach($projectproduction as $ppc){
			$progresstotal += $ppc->progress;
		}
		
		foreach($projectpurchase as $pp){
			$m2 = (( $pp->product->type->length * $pp->product->type->width ) / 10000) * $pp->product->carton_pcs;
			
			if($pp->product->type->category->parent()->parent()->slug == 'tile'){
				$total += $pp->price * $pp->qty * $m2;
			}else{
				$total += $pp->price * $pp->qty;
			}
        }
		
		foreach($project->projectPurchasePayment as $ppp){
			$totalpaid += $ppp->nominal;
			$datapayment[] = [
				'date'		=> $ppp->date,
				'bank'		=> $ppp->coa->name,
				'nominal'	=> $ppp->projectPurchase->currency->symbol.' '.number_format($ppp->nominal,2,',','.'),
				'status'	=> $ppp->status()
			];
		}
		
		return response()->json([
			'datapayment'	=> $datapayment,
			'progress_left' => $progressleft - $progresstotal,
			'supplier_name' => $project->supplier->name,
			'total' => $project->currency->symbol.' '.number_format($total,2,',','.'),
			'totalraw' => $total,
			'totalpaid' => $project->currency->symbol.' '.number_format($totalpaid,2,',','.'),
			'totalleft' => number_format($total - $totalpaid,2,',','.'),
		]);
	}
	
	public function getSalesInfo(Request $request)
	{
		$projectsale = ProjectSale::find($request->idprojectsale);
		$sales_id = $projectsale->sales_id;
		$customer_id = $projectsale->project->customer_id;
		
		$totalpaid = 0;
		$total = 0;
		$grandtotal = 0;
		
		foreach($projectsale->projectSalePay as $psp){
			$totalpaid += $psp->nominal;
		}
		
		foreach($projectsale->projectSaleProduct as $pp){
			if($pp->product->type->category->parent()->parent()->slug == 'tile'){
				$m2 = (( $pp->product->type->length * $pp->product->type->width ) / 10000) * $pp->product->carton_pcs;
				$countbox = ceil($pp->qty / $m2);
				$total += $pp->best_price * $m2 * $countbox;
			}else if($pp->product->type->category->parent()->parent()->slug !== 'tile'){
				$total += $pp->best_price * $pp->qty;
			}
		}
		$total = $total + $projectsale->delivery_cost + $projectsale->cutting_cost + $projectsale->misc_cost;
		
		$ppn = 0;
		
		if($projectsale->project->ppn == '1'){
			$ppn = 0.1 * $total;
		}
		
		$grandtotal = $ppn + $total;
		
		return response()->json([
			'sales_id' => $sales_id,
			'sales_address' => $projectsale->address,
			'sales_note' => $projectsale->note,
			'sales_name' => $projectsale->sales->name,
			'customer_id' => $customer_id,
			'customer_name' => $projectsale->project->customer->name,
			'customer_email' => $projectsale->project->customer->email,
			'customer_phone' => $projectsale->project->customer->phone,
			'city_id' => $projectsale->project->city_id,
			'city_name' => $projectsale->project->city->name,
			'payment_total' => number_format($grandtotal,2,',','.'),
			'payment_paid' => number_format($totalpaid,2,',','.'),
			'payment_left' => number_format($grandtotal - $totalpaid,2,',','.')
		]);
	}
	
	public function getSupplierCurrency(Request $request)
	{
		$supplier = Supplier::find($request->idsupp);
		
		$data = [];
		
		foreach($supplier->supplierCurrency as $sc){
			$data[] = [
				'id' 		=> $sc->currency->id,
				'code' 		=> $sc->currency->code,
				'name'		=> $sc->currency->name,
				'symbol'	=> $sc->currency->symbol
			];
		}
		
		return response()->json($data);
	}
	
	public function getShipmentInfo(Request $request)
	{
		$projectshipment = ProjectShipment::where('project_purchase_id',$request->idpo)->get();
		
		
		$listshipment = [];
		
		foreach($projectshipment as $ps){
			$listshipment[] = [
				'shipment_id' 	=> $ps->id,
				'shipment_code'	=> $ps->shipment_code
			];
		}
		
		return response()->json([
			'shipment_list'		=> $listshipment,
		]);
	}
	
	public function getShipmentProduct(Request $request)
	{
		$projectshipmentproduct = ProjectShipmentProduct::where('project_shipment_id',$request->idshipment)->get();
		
		$listproduct = [];
		
		foreach($projectshipmentproduct as $psp){
			$listproduct [] = [
				'product_id'	=> $psp->product_id,
				'product_name'	=> $psp->product->name(),
				'qty'			=> $psp->qty,
				'unitraw'		=> $psp->unit,
				'unit'			=> $psp->unit()
			];
		}
		
		return response()->json([
			'shipment_product'	=> $listproduct
		]);
	}
	
	public function getShadingProduct(Request $request){
		$productshading = ProductShading::where('product_id',$request->val)->orderByDesc('qty')->get();
		$productname =	Product::find($request->val);
		
		$shading = [];
		$product_name = $productname->name();
		
		foreach($productshading as $ps){
			$shading[] = [
				'id'					=> $ps->id,
				'product_id'			=> $ps->product_id,
				'warehouse_code'		=> $ps->warehouse_code,
				'warehouse_name'		=> $ps->warehouse->name,
				'stock_code'			=> $ps->stock_code,
				'code'					=> $ps->code,
				'qty'					=> $ps->qty,
				'unit'					=> $ps->product->type->stockUnit->name
			];
		}
		
		return response()->json([
			'shading'		=> $shading,
			'product_name'	=> $product_name
		]);
	}
	
	public function updateStatusSample(Request $request){
		$projectsample = ProjectSample::find($request->id);
		$projectsample->status = $request->val;
		
		if($request->val == '1'){
			$projectsample->returned_at = null;
		}elseif($request->val == '2'){
			$projectsample->returned_at = now();
		}
		
		$projectsample->save();
		
		activity()
			->performedOn(new ProjectSample())
			->causedBy(session('bo_id'))
			->withProperties($projectsample)
			->log('Change status sample product');
		
		return response()->json([
			'status'  => 200,
			'message' => 'Data added successfully.'
		]);
	}
	
	public function skipForm(Request $request){
		$project = Project::find($request->project);
		
		$step = $request->step;
		
		if($step == 5){
			$project->progress = $project->progress < 30 ? 30 : $project->progress;
		}else{
			$project->progress = $project->progress;
		}
	
		$project->save();
		
		activity()
			->performedOn(new Project())
			->causedBy(session('bo_id'))
			->withProperties($project)
			->log('Skip project Step '.$step);
		
		return response()->json([
			'status'  => 200,
			'message' => 'Data added successfully.'
		]);
	}
	
	public function addShipmentTracking(Request $request){
		
		$trackshipment = ProjectShipmentTrack::create([
			'user_id'				=> session('bo_id'),
			'project_shipment_id'	=> $request->id,
			'note'         			=> $request->note
		]);
		
		$shipmenttrack = ProjectShipmentTrack::where('project_shipment_id',$request->id)->get();
		
		activity()
			->performedOn(new ProjectShipmentTrack())
			->causedBy(session('bo_id'))
			->withProperties($trackshipment)
			->log('Add tracking to shipment');
		
		return response()->json($shipmenttrack);
	}
	
	public function deleteShipmentTracking(Request $request){
		
		$trackingshipment = ProjectShipmentTrack::find($request->id)->delete();
		
		activity()
			->performedOn(new ProjectShipmentTrack())
			->causedBy(session('bo_id'))
			->withProperties($trackingshipment)
			->log('Delete tracking to shipment');
			
		return response()->json([
			'status'  => 200,
			'message' => 'Data added successfully.'
		]);
	}
	
	public function getShipmentTracking(Request $request){
		$shipmenttrack = ProjectShipmentTrack::where('project_shipment_id',$request->id)->get();
		
		return response()->json($shipmenttrack);
	}

	public function getDeliveryTracking(Request $request){
		$deliverytrack = ProjectDeliveryTrack::where('project_delivery_id',$request->id)->get();
		
		$data = [];
		
		foreach($deliverytrack as $row){
			$data[] = [
				'id'					=> $row->id,
				'user'					=> $row->user->name,
				'project_delivery_id'	=> $row->project_delivery_id,
				'note'					=> $row->note,
				'image'					=> $row->image ? '<a href="' . $row->image() . '" data-lightbox="' . $row->projectDelivery->code . '" data-title="' . $row->projectDelivery->code . '"><img src="' . $row->image() . '" style="max-width:70px;" class="img-fluid img-thumbnail mb-2"></a>' : '<span class="badge badge-secondary">None</span>',
				'created_at'			=> $row->created_at
			];
		}
		
		return response()->json($data);
	}
	
	public function addDeliveryTracking(Request $request){
		
		$trackdelivery = ProjectDeliveryTrack::create([
			'user_id'				=> session('bo_id'),
			'project_delivery_id'	=> $request->id,
			'note'         			=> $request->note,
			'image'					=> $request->file('file') ? $request->file('file')->store('public/project') : ''
		]);
		
		$deliverytrack = ProjectDeliveryTrack::where('project_delivery_id',$request->id)->get();
		
		$data = [];
		
		foreach($deliverytrack as $row){
			$data[] = [
				'id'					=> $row->id,
				'user'					=> $row->user->name,
				'project_delivery_id'	=> $row->project_delivery_id,
				'note'					=> $row->note,
				'image'					=> $row->image ? '<a href="' . $row->image() . '" data-lightbox="' . $row->projectDelivery->code . '" data-title="' . $row->projectDelivery->code . '"><img src="' . $row->image() . '" style="max-width:70px;" class="img-fluid img-thumbnail mb-2"></a>' : '<span class="badge badge-secondary">None</span>',
				'created_at'			=> $row->created_at
			];
		}
		
		activity()
			->performedOn(new ProjectDeliveryTrack())
			->causedBy(session('bo_id'))
			->withProperties($trackdelivery)
			->log('Add tracking to delivery');
		
		return response()->json($data);
	}
	
	public function deleteDeliveryTracking(Request $request)
	{
		$trackingdelivery = ProjectDeliveryTrack::find($request->id);
		
		if(Storage::exists($trackingdelivery->image)) {
			Storage::delete($trackingdelivery->image);
        } 
		
		$trackingdelivery->delete();
		
		activity()
			->performedOn(new ProjectDeliveryTrack())
			->causedBy(session('bo_id'))
			->withProperties($trackingdelivery)
			->log('Delete tracking to delivery project');
			
		return response()->json([
			'status'  => 200,
			'message' => 'Data added successfully.'
		]);
	}
	
	public function trackingShipment(Request $request)
	{
		
		$shipment = ProjectShipment::where('id',$request->id)
									->where('shipment_code',$request->code)
									->first();
		
		if(!$shipment) {
			abort(404);
		}
		
		$data = [
			'title'   	=> 'Detail Tracking '.$request->code,
			'track' 	=> $shipment->projectShipmentTrack,
			'finish'	=> $shipment->projectShipmentWarehouse
		];
		
		return view('admin.tracking.shipment', $data);
	}
	
	public function trackingDelivery(Request $request)
	{
		
		$delivery = ProjectDelivery::where('id',$request->id)
									->where('code',str_replace('-','/',$request->code))
									->first();
		
		if(!$delivery) {
			abort(404);
		}
		
		$data = [
			'title'   	=> 'Detail Tracking '.str_replace('-','/',$request->code),
			'track' 	=> $delivery->projectDeliveryTrack
		];
		
		return view('admin.tracking.delivery', $data);
	}
	
	public function purchaseOrderReport() 
    {
		$data = [
			'title'   => 'Purchase Order Project Report',
			'content' => 'admin.report.purchase_order.project'
		];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function datatable_purchase(Request $request) 
    {
		$uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
		$uri = explode('/', $uri_path);
		
        $column = [
            'id',
            'code',
			'project',
            'user_id',
            'name',
            'progress'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = ProjectPurchase::count();
        
        $query_data = ProjectPurchase::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('code', 'like', "%$search%");
                    });
                }  
            })
			->orWhere(function($query) use ($search, $request) {
				if($search) {
					$query->whereHas('project', function($query) use ($search) {
						$query->where('name', 'like', "%$search%");
					});
				}
			})
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = ProjectPurchase::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('code', 'like', "%$search%");
                    });
                }      
            })
			->orWhere(function($query) use ($search, $request) {
				if($search) {
					$query->whereHas('project', function($query) use ($search) {
						$query->where('name', 'like', "%$search%");
					});
				}
			})
            ->count();

        $response['data'] = [];
		
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
				$action = '<a href="' . url('admin/report/purchase_order/project/detail/' . $val->id) . '" class="btn bg-warning btn-sm" data-popup="tooltip" title="Progress"><i class="icon-zoomin3"></i></a>';

				$progress_count = isset($val->project) ? $val->project->progress : 0;
				$progress = '
					<div class="progress" style="height:0.875rem;">
						<div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" style="width:100%;">
							<span class="font-weight-bold text-uppercase">
								<span style="font-size:13px;">' . $progress_count . '%</span>
							</span>
						</div>
					</div>
				';

                $response['data'][] = [
                    $nomor,
					$val->code,
                    $val->project ? $val->project->code : 'FOR STOCK',
                    $val->project ? $val->project->user->name : 'FOR STOCK',
                    $val->project ? $val->project->name : 'FOR STOCK',
                    $progress,
                    $action
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
	
	public function detailPurchaseOrderReport(Request $request) 
	{
		$data = [
			'title'   	=> 'Progress Report Purchase Order',
			'project'	=> ProjectPurchase::find($request->id),
			'content' 	=> 'admin.report.purchase_order.project_detail'
		];

        return view('admin.layouts.index', ['data' => $data]);
	}
	
	public function warehouseReceiveReport(Request $request) 
    {
		$data = [
			'title'   => 'Warehouse Receive Project Report',
			'content' => 'admin.report.purchase_order.warehouse_receive',
		];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function datatableWarehouseReceive(Request $request) 
    {
		$column = [
            'id'
        ];
		
		$filter_month = $request->filter_month ? $request->filter_month : '';
        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = ProjectWarehouse::whereRaw('SUBSTR(created_at, 1, 7) = "' . $filter_month . '"')->count();
        
        $query_data = ProjectWarehouse::whereRaw('SUBSTR(date_receive, 1, 7) = "' . $filter_month . '"')
		->where(function ($query) use ($search, $request) {
			if($search){
				$query->where('date_receive', 'like', "%$search%")
				->orWhere('code', 'like', "%$search%")
				->orWhereHas('projectPurchase',function ($query) use ($search, $request){
					$query->where('code', 'like', "%$search%");
				});
			}
		 })
			->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = ProjectWarehouse::whereRaw('SUBSTR(date_receive, 1, 7) = "' . $filter_month . '"')
		->where(function ($query) use ($search, $request) {
			if($search){
				$query->where('date_receive', 'like', "%$search%")
				->orWhere('code', 'like', "%$search%")
				->orWhereHas('projectPurchase',function ($query) use ($search, $request){
					$query->where('code', 'like', "%$search%");
				});
			}
		 })
			->count();

        $response['data'] = [];
		
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
				foreach($val->projectWarehouseProduct as $row){					
					
					if($val->projectPurchase->projectSale){
						$total = $val->projectPurchase->projectSale->projectSaleProduct->where('product_id',$row->product_id)->first()->qty * $val->projectPurchase->projectPurchaseProduct->where('product_id',$row->product_id)->first()->price;
					}else{
						$total = $val->projectPurchase->projectPurchaseProduct->where('product_id',$row->product_id)->first()->price;
					}
					
					
					$response['data'][] = [
						$nomor,
						date('d M Y',strtotime($val->date_receive)),
						$val->code,
						$val->projectPurchase->code,
						$val->projectPurchase->projectSale ? $val->projectPurchase->projectSale->code : '',
						$val->projectPurchase->projectSale ? $val->projectPurchase->projectSale->project->customer->name : '',
						$val->projectPurchase->projectSale ? $val->projectPurchase->projectSale->sales->name : '',
						$val->projectPurchase->supplier->name,
						$val->projectPurchase->projectProforma ? '<a href="'.($val->projectPurchase->projectProforma->first() ? $val->projectPurchase->projectProforma->first()->attachment() : '').'" target="_blank">Detail</a>': '',
						$row->product->name(),
						$row->qty.' '.$row->unit(),
						$val->projectPurchase->projectSale ? $val->projectPurchase->projectSale->projectSaleProduct->where('product_id',$row->product_id)->first()->qty : '',
						'Rp'.number_format($val->projectPurchase->projectPurchaseProduct->where('product_id',$row->product_id)->first()->price,0,',','.'),
						'Rp'.number_format($total,0,',','.'),
						'Rp'.number_format(($total)/1.1,0,',','.'),
						'Rp'.number_format($total - ($total/1.1),0,',','.')
					];
					
					$nomor++;
				}
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
	
}