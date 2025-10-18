<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Coa;
use App\Models\Product;
use App\Models\Project;
use App\Models\CurrencyRate;
use App\Models\Currency;
use App\Models\BudgetingProject;
use App\Models\PurchaseRequest;
use App\Models\BudgetingProjectDetail;
use App\Models\BudgetingProjectProduct;
use App\Models\Approval;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Helper\SendMessage;
use App\Models\ProjectWarehouseProduct;

class BudgetingProjectController extends Controller
{

	public function index(Request $request)
	{
		$data = [
			'title'   			=> 'Budget Plan Projection',
			'coa'     			=> Coa::orderBy('code')->get(),
			'budgeting_project' => BudgetingProject::all(),
			'currency' 			=> Currency::where('status', '1')->get(),
			'project'			=> $request->project_id ? Project::find($request->project_id) : '',
			'content' 			=> 'admin.sales.budgeting_project'
		];

		return view('admin.layouts.index', ['data' => $data]);
	}

	public function create(Request $request)
	{
		$validation = Validator::make($request->all(), [
			'project_id'           	=> 'required',
			'name'           		=> 'required',
			'branch'				=> 'required',
			'startmonth'	 		=> 'required',
			'endmonth' 	 	 		=> 'required',
			'coa_detail' 			=> 'required',
			'nominal_detail' 		=> 'required',
			'group_detail'    		=> 'required',
			'remarks'				=> 'required'
		], [
			'project_id.required'           => 'Project cannot be empty.',
			'name.required'           		=> 'Name cannot be empty.',
			'branch.required'           	=> 'Branch cannot be empty.',
			'startmonth.required'     		=> 'Project month start cannot be empty.',
			'endmonth.required' 	  		=> 'Project month end cannot be empty.',
			'coa_detail.required' 	  		=> 'Coa transaction cannot be empty.',
			'nominal_detail.required' 		=> 'Nominal coa cannot be empty.',
			'group_detail.required'    		=> 'Group detail cannot be empty.',
			'remarks.required'				=> 'Remarks cannot be empty.'
		]);

		if ($validation->fails()) {
			$response = [
				'status' => 422,
				'error'  => $validation->errors()
			];
		} else {

			$cek = BudgetingProject::where('project_id', $request->project_id)->first();

			if ($cek) {
				$response = [
					'status'  => 500,
					'message' => 'Project already has budgeting data.'
				];

				return response()->json($response);
			}

			$query = BudgetingProject::create([
				'user_id'     		=> session('bo_id'),
				'project_id'  		=> $request->project_id,
				'name'  			=> $request->name,
				'branch'			=> $request->branch,
				'month_start'		=> $request->startmonth,
				'month_end'			=> $request->endmonth,
				'percent_rental'	=> str_replace(',', '.', str_replace('.', '', $request->percent_rental)),
				'percent_fixed'		=> str_replace(',', '.', str_replace('.', '', $request->percent_fixed)),
				'percent_rsv'		=> str_replace(',', '.', str_replace('.', '', $request->percent_rsv)),
				'percent_ipm'		=> str_replace(',', '.', str_replace('.', '', $request->percent_ipm)),
				'percent_ibc'		=> str_replace(',', '.', str_replace('.', '', $request->percent_ibc)),
				'percent_import'	=> str_replace(',', '.', str_replace('.', '', $request->percent_import)),
				'percent_safe'		=> str_replace(',', '.', str_replace('.', '', $request->percent_safe)),
				'percent_ppn'		=> str_replace(',', '.', str_replace('.', '', $request->percent_ppn)),
				'percent_pph'		=> str_replace(',', '.', str_replace('.', '', $request->percent_pph)),
				'percent_mkj'		=> str_replace(',', '.', str_replace('.', '', $request->percent_mkj)),
				'percent_pta'		=> str_replace(',', '.', str_replace('.', '', $request->percent_pta)),
				'percent_mid'		=> str_replace(',', '.', str_replace('.', '', $request->percent_mid)),
				'percent_scom'		=> str_replace(',', '.', str_replace('.', '', $request->percent_scom)),
				'currency_id'		=> $request->currency_id,
				'help_exchange_rate' => str_replace(',', '.', str_replace('.', '', $request->exchange_rate)),
				'help_container_no'	=> str_replace(',', '.', str_replace('.', '', $request->number_container)),
				'help_ls_cost'		=> str_replace(',', '.', str_replace('.', '', $request->ls_cost)),
				'help_product_cost'	=> str_replace(',', '.', str_replace('.', '', $request->product_total)),
				'help_container_qty' => str_replace(',', '.', str_replace('.', '', $request->qty_container)),
				'help_freight_cost'	=> str_replace(',', '.', str_replace('.', '', $request->freight_cost)),
				'help_emkl_cost'	=> str_replace(',', '.', str_replace('.', '', $request->emkl)),
				'remarks'			=> $request->remarks
			]);

			if ($query) {
				foreach ($request->coa_detail as $key => $dd) {
					BudgetingProjectDetail::create([
						'budgeting_project_id' 	=> $query->id,
						'coa_id'       			=> $dd,
						'nominal'      			=> str_replace(',', '.', str_replace('.', '', $request->nominal_detail[$key])),
						'group_count'			=> $request->group_detail[$key],
						'description'  			=> $request->description_detail[$key]
					]);
				}

				foreach ($request->product_id as $key => $pr) {
					BudgetingProjectProduct::create([
						'budgeting_project_id' 	=> $query->id,
						'product_id'       		=> $pr,
						'qty'      				=> $request->product_qty[$key],
						'unit'					=> $request->product_unit[$key],
						'price'					=> str_replace(',', '.', str_replace('.', '', $request->product_price[$key])),
						'sell_price'			=> str_replace(',', '.', str_replace('.', '', $request->product_price_sell[$key])),
						'total'					=> str_replace(',', '.', str_replace('.', '', $request->product_price[$key])) * $request->product_qty[$key],
						'total_sell'			=> str_replace(',', '.', str_replace('.', '', $request->product_price_sell[$key])) * $request->product_qty[$key],
					]);
				}

				#send approval
				/* $roleapproval = array('1');
				Approval::sendApproval($roleapproval,'budgeting_projects',$query->id,'approved_by',session('bo_id')); */

				$roleapproval = array('4');
				Approval::sendApproval($roleapproval, 'budgeting_projects', $query->id, 'checked_by', session('bo_id'));
				#end send approval

				/* SendMessage::send(env('OWNER_PHONE'),'Halo pak/bu. Mohon dibantu approve Budgeting Project Nomor '.$query->project->code.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.'); */
				SendMessage::send(env('ACCOUNTING_PHONE'), 'Halo pak/bu. Mohon dibantu approve Budgeting Project Nomor ' . $query->project->code . '. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');

				activity()
					->performedOn(new BudgetingProject())
					->causedBy(session('bo_id'))
					->withProperties($query)
					->log('Add budgeting plan project');

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

	public function datatable(Request $request)
	{
		$column = [
			'id',
			'user_id',
			'project_id',
			'name',
			'branch',
			'period',
			'nominal',
			'approved_by',
			'checked_by'
		];

		$start  = $request->start;
		$length = $request->length;
		$order  = $column[$request->input('order.0.column')];
		$dir    = $request->input('order.0.dir');
		$search = $request->input('search.value');

		$total_data = BudgetingProject::count();

		$query_data = BudgetingProject::where(function ($query) use ($search, $request) {
			if ($search) {
				$query->where(function ($query) use ($search) {
					$query->whereHas('user', function ($query) use ($search) {
						$query->where('name', 'like', "%$search%");
					})->orWhereHas('project', function ($query) use ($search) {
						$query->where('name', 'like', "%$search%")
							->orWhere('code', 'like', "%$search%")
							->orWhereHas('customer', function ($query) use ($search) {
								$query->where('name', 'like', "%$search%");
							});
					});
				})->orWhere('name', 'like', "%$search%");
			}

			if ($request->start_date && $request->finish_date) {
				$query->where('month_start', '>=', $request->start_date)
					->where('month_end', '<=', $request->finish_date);
			} elseif ($request->start_date) {
				$query->where('month_start', '=', $request->start_date);
			} elseif ($request->finish_date) {
				$query->where('month_end', '=', $request->start_date);
			}

			if ($request->branch) {
				$query->where('branch', $request->branch);
			}
		})
			->offset($start)
			->limit($length)
			->orderBy($order, $dir)
			->get();

		$total_filtered = BudgetingProject::where(function ($query) use ($search, $request) {
			if ($search) {
				$query->where(function ($query) use ($search) {
					$query->whereHas('user', function ($query) use ($search) {
						$query->where('name', 'like', "%$search%");
					});
				})->orWhere('name', 'like', "%$search%");
			}

			if ($request->start_date && $request->finish_date) {
				$query->where('month_start', '>=', $request->start_date)
					->where('month_end', '<=', $request->finish_date);
			} elseif ($request->start_date) {
				$query->where('month_start', '=', $request->start_date);
			} elseif ($request->finish_date) {
				$query->where('month_end', '=', $request->start_date);
			}

			if ($request->branch) {
				$query->where('branch', $request->branch);
			}
		})
			->count();

		$response['data'] = [];
		if ($query_data <> FALSE) {
			$nomor = $start + 1;
			foreach ($query_data as $val) {

				$period = date('F, Y', strtotime($val->month_start)) . ' - ' . date('F, Y', strtotime($val->month_end));

				$response['data'][] = [
					$nomor,
					$val->user->name,
					$val->project ? $val->project->code . ' - ' . $val->project->name : 'Project Deleted.',
					$val->name,
					$val->branch(),
					$period,
					number_format($val->getTotalRevenue(), 0, ',', '.'),
					$val->approved_by ? $val->approved->name : '<a href="javascript:void(0);" onclick="sendMessage(`' . env('ACCOUNTING_PHONE') . '`,``)" class="btn btn-success btn-icon rounded-pill"><i class="icon-phone2"></i></a>',
					$val->checked_by ? $val->checked->name : '<a href="javascript:void(0);" onclick="sendMessage(`' . env('ACCOUNTING_PHONE') . '`,' . $val->getApprovalAccounting() . ')" class="btn btn-success btn-icon rounded-pill"><i class="icon-phone2"></i></a>',
					'
						<a type="button" class="btn bg-info btn-sm" href="' . url('admin/sales/budgeting_project/detail/' . $val->id) . '"><i class="icon-info22"></i></a>
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

	public function show(Request $request)
	{
		$bp = BudgetingProject::find($request->id);

		$bp['project_info'] = $bp->project ? $bp->project->code . ' ' . $bp->project->name . ' Cust ' . $bp->project->customer->name . ' Rp ' . number_format($bp->project->getTotalSale(), 0, ',', '.') : 'Project Deleted.';

		$bpd = [];

		foreach ($bp->budgetingProjectDetail as $row) {
			$bpd[] = [
				'coa_id' 		=> $row->coa_id,
				'coa_name'		=> $row->group_count == '5' ? $row->coa()["name"] : '[' . $row->coa["code"] . '] ' . $row->coa["name"],
				'nominal'		=> number_format($row->nominal, 0, ',', '.'),
				'group_count'	=> $row->group_count,
				'description'	=> $row->description == NULL ? '' : $row->description
			];
		}

		$bpr = [];

		foreach ($bp->budgetingProjectProduct as $row) {
			$bpr[] = [
				'id'      			=> $row->product_id,
				'product' 			=> $row->product->name(),
				'purchase_price'	=> number_format($row->price, 2, ',', '.'),
				'sell_price'		=> number_format($row->sell_price, 2, ',', '.'),
				'total'				=> number_format($row->total, 2, ',', '.'),
				'total_sell'		=> number_format($row->total_sell, 2, ',', '.'),
				'bottom'  			=> 0,
				'surface' 			=> $row->product->type->surface->name,
				'carton_pcs' 		=> $row->product->carton_pcs,
				'qty'				=> $row->qty,
				'unit'				=> $row->unit(),
				'unitraw'			=> $row->unit,
				'sqm'				=> (($row->product->type->length * $row->product->type->width) / 10000) * $row->product->carton_pcs,
				'carton_sqm' 		=> (($row->product->type->length * $row->product->type->width) / 10000) * $row->product->carton_pcs . ' M<sup>2</sup>'
			];
		}

		$data = [
			'info' 		=> $bp,
			'detail'	=> $bpd,
			'product'	=> $bpr
		];

		return response()->json($data);
	}

	public function update(Request $request, $id)
	{
		$query      = BudgetingProject::find($id);

		$validation = Validator::make($request->all(), [
			'project_id'           	=> 'required',
			'name'           		=> 'required',
			'branch'           		=> 'required',
			'startmonth'	 		=> 'required',
			'endmonth' 	 	 		=> 'required',
			'coa_detail' 			=> 'required',
			'nominal_detail' 		=> 'required',
			'group_detail'    		=> 'required',
			'remarks'				=> 'required',
		], [
			'project_id.required'           => 'Project name cannot be empty.',
			'name.required'           		=> 'Name cannot be empty.',
			'branch.required'           	=> 'Branch cannot be empty.',
			'startmonth.required'     		=> 'Project month start cannot be empty.',
			'endmonth.required' 	  		=> 'Project month end cannot be empty.',
			'coa_detail.required' 	  		=> 'Coa transaction cannot be empty.',
			'nominal_detail.required' 		=> 'Nominal coa cannot be empty.',
			'group_detail.required'    		=> 'Group detail cannot be empty.',
			'remarks.required'				=> 'Remarks cannot be empty.'
		]);

		if ($validation->fails()) {
			$response = [
				'status' => 422,
				'error'  => $validation->errors()
			];
		} else {
			if ($query->approved_by == null || $query->checked_by == null) {
				$query->update([
					'user_id'     		=> session('bo_id'),
					'project_id'     	=> $request->project_id,
					'name'  			=> $request->name,
					'branch'  			=> $request->branch,
					'month_start'		=> $request->startmonth,
					'month_end'			=> $request->endmonth,
					'revision_counter'	=> $query->revision_counter,
					'percent_rental'	=> str_replace(',', '.', str_replace('.', '', $request->percent_rental)),
					'percent_fixed'		=> str_replace(',', '.', str_replace('.', '', $request->percent_fixed)),
					'percent_rsv'		=> str_replace(',', '.', str_replace('.', '', $request->percent_rsv)),
					'percent_ipm'		=> str_replace(',', '.', str_replace('.', '', $request->percent_ipm)),
					'percent_ibc'		=> str_replace(',', '.', str_replace('.', '', $request->percent_ibc)),
					'percent_import'	=> str_replace(',', '.', str_replace('.', '', $request->percent_import)),
					'percent_safe'		=> str_replace(',', '.', str_replace('.', '', $request->percent_safe)),
					'percent_ppn'		=> str_replace(',', '.', str_replace('.', '', $request->percent_ppn)),
					'percent_pph'		=> str_replace(',', '.', str_replace('.', '', $request->percent_pph)),
					'percent_mkj'		=> str_replace(',', '.', str_replace('.', '', $request->percent_mkj)),
					'percent_pta'		=> str_replace(',', '.', str_replace('.', '', $request->percent_pta)),
					'percent_mid'		=> str_replace(',', '.', str_replace('.', '', $request->percent_mid)),
					'percent_scom'		=> str_replace(',', '.', str_replace('.', '', $request->percent_scom)),
					'currency_id'		=> $request->currency_id,
					'help_exchange_rate' => str_replace(',', '.', str_replace('.', '', $request->exchange_rate)),
					'help_container_no'	=> str_replace(',', '.', str_replace('.', '', $request->number_container)),
					'help_ls_cost'		=> str_replace(',', '.', str_replace('.', '', $request->ls_cost)),
					'help_product_cost'	=> str_replace(',', '.', str_replace('.', '', $request->product_total)),
					'help_container_qty' => str_replace(',', '.', str_replace('.', '', $request->qty_container)),
					'help_freight_cost'	=> str_replace(',', '.', str_replace('.', '', $request->freight_cost)),
					'help_emkl_cost'	=> str_replace(',', '.', str_replace('.', '', $request->emkl)),
					'remarks'			=> $request->remarks,
					'approved_by'		=> 7,
					'checked_by'		=> 7
				]);
			} else {
				$query->update([
					'user_id'     		=> session('bo_id'),
					'project_id'     	=> $request->project_id,
					'name'  			=> $request->name,
					'branch'  			=> $request->branch,
					'month_start'		=> $request->startmonth,
					'month_end'			=> $request->endmonth,
					'revision_counter'	=> $query->revision_counter + 1,
					'percent_rental'	=> str_replace(',', '.', str_replace('.', '', $request->percent_rental)),
					'percent_fixed'		=> str_replace(',', '.', str_replace('.', '', $request->percent_fixed)),
					'percent_rsv'		=> str_replace(',', '.', str_replace('.', '', $request->percent_rsv)),
					'percent_ipm'		=> str_replace(',', '.', str_replace('.', '', $request->percent_ipm)),
					'percent_ibc'		=> str_replace(',', '.', str_replace('.', '', $request->percent_ibc)),
					'percent_import'	=> str_replace(',', '.', str_replace('.', '', $request->percent_import)),
					'percent_safe'		=> str_replace(',', '.', str_replace('.', '', $request->percent_safe)),
					'percent_ppn'		=> str_replace(',', '.', str_replace('.', '', $request->percent_ppn)),
					'percent_pph'		=> str_replace(',', '.', str_replace('.', '', $request->percent_pph)),
					'percent_mkj'		=> str_replace(',', '.', str_replace('.', '', $request->percent_mkj)),
					'percent_pta'		=> str_replace(',', '.', str_replace('.', '', $request->percent_pta)),
					'percent_mid'		=> str_replace(',', '.', str_replace('.', '', $request->percent_mid)),
					'percent_scom'		=> str_replace(',', '.', str_replace('.', '', $request->percent_scom)),
					'currency_id'		=> $request->currency_id,
					'help_exchange_rate' => str_replace(',', '.', str_replace('.', '', $request->exchange_rate)),
					'help_container_no'	=> str_replace(',', '.', str_replace('.', '', $request->number_container)),
					'help_ls_cost'		=> str_replace(',', '.', str_replace('.', '', $request->ls_cost)),
					'help_product_cost'	=> str_replace(',', '.', str_replace('.', '', $request->product_total)),
					'help_container_qty' => str_replace(',', '.', str_replace('.', '', $request->qty_container)),
					'help_freight_cost'	=> str_replace(',', '.', str_replace('.', '', $request->freight_cost)),
					'help_emkl_cost'	=> str_replace(',', '.', str_replace('.', '', $request->emkl)),
					'remarks'			=> $request->remarks,
					'approved_by'		=> 7,
					'checked_by'		=> 7
				]);
			}

			if ($query) {
				BudgetingProjectDetail::where('budgeting_project_id', $query->id)->delete();
				BudgetingProjectProduct::where('budgeting_project_id', $query->id)->delete();

				foreach ($request->coa_detail as $key => $dd) {
					BudgetingProjectDetail::create([
						'budgeting_project_id' 	=> $query->id,
						'coa_id'       			=> $dd,
						'nominal'      			=> str_replace(',', '.', str_replace('.', '', $request->nominal_detail[$key])),
						'group_count'			=> $request->group_detail[$key],
						'description'  			=> $request->description_detail[$key]
					]);
				}

				foreach ($request->product_id as $key => $pr) {
					BudgetingProjectProduct::create([
						'budgeting_project_id' 	=> $query->id,
						'product_id'       		=> $pr,
						'qty'      				=> $request->product_qty[$key],
						'unit'					=> $request->product_unit[$key],
						'price'					=> str_replace(',', '.', str_replace('.', '', $request->product_price[$key])),
						'sell_price'			=> str_replace(',', '.', str_replace('.', '', $request->product_price_sell[$key])),
						'total'					=> str_replace(',', '.', str_replace('.', '', $request->product_price[$key])) * $request->product_qty[$key],
						'total_sell'			=> str_replace(',', '.', str_replace('.', '', $request->product_price_sell[$key])) * $request->product_qty[$key],
					]);
				}

				Approval::where('approvalable_type', 'budgeting_projects')->where('approvalable_id', $query->id)->delete();

				#send approval
				/* $roleapproval = array('1');
				Approval::sendApproval($roleapproval,'budgeting_projects',$query->id,'approved_by',session('bo_id')); */
				$roleapproval = array('4');
				Approval::sendApproval($roleapproval, 'budgeting_projects', $query->id, 'checked_by', session('bo_id'));
				#end send approval

				/* SendMessage::send(env('OWNER_PHONE'),'Halo pak David. Mohon dibantu approve Budgeting Project Nomor '.$query->project->code.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.'); */
				SendMessage::send(env('ACCOUNTING_PHONE'), 'Halo pak Ryan. Mohon dibantu approve Budgeting Project Nomor ' . $query->project->code . '. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');

				activity()
					->performedOn(new BudgetingProject())
					->causedBy(session('bo_id'))
					->withProperties($query)
					->log('Update budgeting plan project');

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

	public function detail(Request $request)
	{
		$bp = BudgetingProject::find($request->id);

		if ($bp) {
			$data = [
				'title'   	=> 'Budget Plan Projection Detail',
				'budget'	=> $bp,
				'content' 	=> 'admin.sales.budgeting_project_detail'
			];

			return view('admin.layouts.index', ['data' => $data]);
		} else {
			abort(404);
		}
	}

	public function project(Request $request)
	{
		$project = Project::find($request->id);

		$bp = BudgetingProject::where('project_id', $request->id)->get();

		if ($bp) {
			$data = [
				'title'   	=> 'Budget Plan Projection Detail',
				'project'	=> $project,
				'budget'	=> $bp,
				'content' 	=> 'admin.sales.budgeting_project_list'
			];

			return view('admin.layouts.index', ['data' => $data]);
		} else {
			abort(404);
		}
	}

	public function destroy(Request $request)
	{
		$query = BudgetingProject::find($request->id);

		if ($query->approved_by || $query->checked_by) {
			$response = [
				'status'  => 500,
				'message' => 'This budgeting already approved or checked.'
			];

			return response()->json($response);
		}

		$pr = PurchaseRequest::where('link_type', 'budgeting_projects')->where('link_id', $request->id)->first();

		if ($pr) {
			$response = [
				'status'  => 500,
				'message' => 'This budgeting already has purchase request, delete it first.'
			];

			return response()->json($response);
		}

		if ($query->delete()) {
			BudgetingProjectDetail::where('budgeting_project_id', $request->id)->delete();
			BudgetingProjectProduct::where('budgeting_project_id', $request->id)->delete();

			activity()
				->performedOn(new BudgetingProject())
				->causedBy(session('bo_id'))
				->log('Delete the budgeting project data');

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

	public function updateEstimation(Request $request)
	{
		$query = BudgetingProject::find($request->estimationId);

		$validation = Validator::make($request->all(), [
			'idDetail' 			=> 'required',
			'estimationDetail'	=> 'required'
		], [
			'idDetail.required'        	=> 'Detail Budget cannot be empty.',
			'estimationDetail.required' => 'Estimation Nominal cannot be empty.'
		]);

		if ($query->estimation_counter == 3) {

			$response = [
				'status'  => 500,
				'message' => 'You have reached your revision times.'
			];
		} else {
			if ($validation->fails()) {
				$response = [
					'status' => 422,
					'error'  => $validation->errors()
				];
			} else {

				BudgetingProject::find($request->estimationId)->update([
					'estimation_name' => $request->estimationName,
					'estimation_counter' => $query->estimation_counter + 1
				]);

				foreach ($request->idDetail as $key => $row) {
					BudgetingProjectDetail::find($row)->update([
						'estimation' => str_replace(',', '.', str_replace('.', '', $request->estimationDetail[$key])),
					]);
				}

				Approval::where('approvalable_type', 'budgeting_projects')->where('approvalable_id', $query->id)->delete();

				#send approval
				$roleapproval = array('4');
				Approval::sendApproval($roleapproval, 'budgeting_projects', $query->id, 'approved_by', session('bo_id'));
				$roleapproval = array('4');
				Approval::sendApproval($roleapproval, 'budgeting_projects', $query->id, 'checked_by', session('bo_id'));
				#end send approval

				// SendMessage::send(env('OWNER_PHONE'),'Halo pak David. Mohon dibantu approve Budgeting Project Nomor '.$query->project->code.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');
				SendMessage::send(env('ACCOUNTING_PHONE'), 'Halo pak Ryan. Mohon dibantu approve Budgeting Project Nomor ' . $query->project->code . '. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');

				activity()
					->performedOn(new BudgetingProjectDetail())
					->causedBy(session('bo_id'))
					->log('Update budgeting plan project set Estimation Nominal');

				$response = [
					'status'  => 200,
					'message' => 'Data updated successfully.'
				];
			}
		}

		return response()->json($response);
	}

	public function getProjectProduct(Request $request)
	{
		$project = Project::find($request->id);
		$branch = $request->branch ? $request->branch : 1;
		$arr = [];

		foreach ($project->projectProduct as $row) {
			$projectWarehouseProduct =  ProjectWarehouseProduct::where('product_id', $row->product->id)->whereHas('projectWarehouse', function ($query) use ($branch) {
				$query->whereHas('projectPurchase', function ($query) use ($branch) {
					$query->whereHas('sales', function ($query) use ($branch) {
						$query->where('branch', $branch);
					});
				});
			})
				->latest()
				->first();
				
			$budgetingLatestPurchaseProduct =   BudgetingProjectProduct::whereHas('budgetingProject', function ($query) use ($branch) {
				$query->where('branch', $branch);
			})->where('product_id', $row->product_id)->latest()->first();

			// Gunakan harga AVG produk saat barang masih ada stock, gunakan harga beli Produk terakhir di Budgeting terkahir saat produk tidak mempunyai stock
			$latestPrice = isset($projectWarehouseProduct) ? ($this->calculateLatestPrice($projectWarehouseProduct) > 0 ? $this->calculateLatestPrice($projectWarehouseProduct) : ($budgetingLatestPurchaseProduct ? $budgetingLatestPurchaseProduct->getLatestPrice() : 0)) : ($budgetingLatestPurchaseProduct ? $budgetingLatestPurchaseProduct->getLatestPrice() : 0);

			$arr[] = [
				'id'      			=> $row->product_id,
				'product' 			=> $row->product->name(),
				'purchase_price'	=> $latestPrice > 0 ? number_format($latestPrice, 2, ',', '.') : 0,
				'sell_price'		=> $row->best_price ? number_format($row->best_price, 0, ',', '.') : ($row->recommended_price ? number_format($row->recommended_price, 0, ',', '.') : ($row->price ? number_format($row->price, 0, ',', '.') : 0)),
				'bottom'  			=> 0,
				'surface' 			=> $row->product->type->surface->name,
				'carton_pcs' 		=> $row->product->carton_pcs,
				'qty'				=> $row->qty,
				'unit'				=> $row->unit(),
				'unitraw'			=> $row->unit,
				'sqm'				=> (($row->product->type->length * $row->product->type->width) / 10000) * $row->product->carton_pcs,
				'carton_sqm' 		=> (($row->product->type->length * $row->product->type->width) / 10000) * $row->product->carton_pcs . ' M<sup>2</sup>'
			];
		}

		$response = [
			'product'			=> $arr,
			'branch' 			=> isset($project->sales) ?  $project->sales->branch :  1,
			'delivery_cost'		=> $project->delivery_cost ? number_format($project->delivery_cost, 2, ',', '.') : number_format(0, 2, ',', '.'),
		];

		return response()->json($response);
	}


	// Function untuk menghitung harga beli produk dalam meter/ pcs/ box
	private function calculateLatestPrice($projectWarehouseProduct)
	{
		$m2 = (($projectWarehouseProduct->product->type->length * $projectWarehouseProduct->product->type->width) / 10000) * $projectWarehouseProduct->product->carton_pcs;
		$averageBuyPrice = str_replace(',', '.', str_replace('.', '', $projectWarehouseProduct->averageBuyPrice()));

		if ($projectWarehouseProduct->unit == '2' || $projectWarehouseProduct->unit == '3') {
			if ($m2 < 1.1 && $projectWarehouseProduct->product->type->category->parent()->id !== 18) {
				$finalAvgBuyPrice =  $averageBuyPrice;
			} else {
				if ($m2 < 1.1 && date('Y-m', strtotime($projectWarehouseProduct->projectWarehouse->projectPurchase->created_at)) < '2022-06' && $projectWarehouseProduct->product->type->category->parent()->id == 18) {
					$finalAvgBuyPrice =  $averageBuyPrice;
				} else {
					$finalAvgBuyPrice =  $averageBuyPrice / $m2;
				}
			}
		} elseif ($projectWarehouseProduct->unit == '1' || $projectWarehouseProduct->unit == '4') {
			$finalAvgBuyPrice =  $averageBuyPrice;
		}


		return $finalAvgBuyPrice;
	}
}
