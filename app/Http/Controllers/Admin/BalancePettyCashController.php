<?php

namespace App\Http\Controllers\Admin;

use PDF;
use App\Models\Coa;
use App\Models\CashBank;
use App\Models\Journal;
use App\Models\CashBankDetail;
use App\Models\ProjectPay;
use App\Models\ProjectMainPayment;
use App\Models\BalanceHistory;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestPayment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Helper\CheckCutOff;

class BalancePettyCashController extends Controller
{

	public function index(Request $request)
	{

		// $rowBeforePurchase = PurchaseRequestPayment::where('coa_id', 10)->where('branch', 1)->whereRaw('DATE(date_paid) < "2023-07-01"')->get();

		// $dataBeforePurchase = 0;

		// foreach ($rowBeforePurchase as $row) {
		// 	if (!$row->cekCB() && !$row->purchase_request_main_payment_id) {
		// 		$dataBeforePurchase += $row->nominal;
		// 		echo '<pre>' . var_export($row->id, true) . '</pre>';
		// 		echo '<pre>' . var_export($row->note, true) . '</pre>';
		// 		echo '<pre>' . var_export($row->nominal, true) . '</pre>';
		// 	}
			
		// }
		// dd($dataBeforePurchase);
	

		$balancecoa = [];

		foreach (Coa::where('parent_id', 0)->whereIn('code', ['1.000.00'])->get() as $c) {
			if (count($c->child()) == 0) {
				$balancecoa[] = $c;
			} else {
				foreach ($c->child()->whereNotIn('code', ['1.000.04', '1.000.05', '1.000.06']) as $bc) {
					if (count($bc->child()) == 0) {
						$balancecoa[] = $bc;
					} else {
						foreach ($bc->child()->whereNotIn('code', ['1.000.04', '1.000.05', '1.000.06']) as $bcc) {
							if (count($bcc->child()) == 0) {
								$balancecoa[] = $bcc;
							} else {
								foreach ($bcc->child()->whereNotIn('code', ['1.000.04', '1.000.05', '1.000.06']) as $bccc) {
									if (count($bccc->child()) == 0) {
										$balancecoa[] = $bccc;
									}
								}
							}
						}
					}
				}
			}
		}

		$start_date = $request->start_date ? $request->start_date : '';
		$finish_date = $request->finish_date ? $request->finish_date : '';

		$data = [
			'start_date'	=> $start_date,
			'finish_date'	=> $finish_date,
			'balancecoa'	=> $balancecoa,
			'coa'     		=> Coa::where('status', 1)->oldest('code')->get(),
			'title'   		=> 'Balance Petty Cash',
			'content' 		=> 'admin.finance.balance_petty_cash'
		];

		return view('admin.layouts.index', ['data' => $data]);
	}

	public function datatable(Request $request)
	{
		$column = [
			'id',
			'user_id',
			'branch',
			'nominal',
			'type',
			'date',
			'note',
			'cash_or_bank',
			'coa_id'
		];

		$start  = $request->start;
		$length = $request->length < 0 ? 999999999999999 : $request->length;
		$order  = $column[$request->input('order.0.column')];
		$dir    = $request->input('order.0.dir');
		$search = $request->input('search.value');

		$total_data = BalanceHistory::count();

		$query_data = BalanceHistory::where(function ($query) use ($search, $request) {
			if ($request->start_date && $request->finish_date) {
				$query->whereDate('date', '>=', $request->start_date)
					->whereDate('date', '<=', $request->finish_date);
			} else if ($request->start_date) {
				$query->whereDate('date', $request->start_date);
			} else if ($request->finish_date) {
				$query->whereDate('date', $request->finish_date);
			}

			if ($request->user_id) {
				$query->where('user_id', $request->user_id);
			}

			if ($request->branch) {
				$query->where('branch', $request->branch);
			}

			if ($request->type) {
				$query->where('type', $request->type);
			}

			if ($search) {
				$query->where(function ($query) use ($search) {
					$query->where('nominal', 'like', "%$search%")
						->orWhere('type', 'like', "%$search%")
						->orWhere('date', 'like', "%$search%")
						->orWhere('note', 'like', "%$search%");
				})->orWhere('id', $search);
			}
		})
			->offset($start)
			->limit($length)
			->orderBy($order, $dir)
			->get();

		$total_filtered = BalanceHistory::where(function ($query) use ($search, $request) {
			if ($request->start_date && $request->finish_date) {
				$query->whereDate('date', '>=', $request->start_date)
					->whereDate('date', '<=', $request->finish_date);
			} else if ($request->start_date) {
				$query->whereDate('date', $request->start_date);
			} else if ($request->finish_date) {
				$query->whereDate('date', $request->finish_date);
			}

			if ($request->user_id) {
				$query->where('user_id', $request->user_id);
			}

			if ($request->branch) {
				$query->where('branch', $request->branch);
			}

			if ($request->type) {
				$query->where('type', $request->type);
			}

			if ($search) {
				$query->where(function ($query) use ($search) {
					$query->where('nominal', 'like', "%$search%")
						->orWhere('type', 'like', "%$search%")
						->orWhere('date', 'like', "%$search%")
						->orWhere('note', 'like', "%$search%");
				})->orWhere('id', $search);
			}
		})
			->count();

		$response['data'] = [];
		if ($query_data <> FALSE) {
			$nomor = $start + 1;

			foreach ($query_data as $val) {

				$image = '';

				if ($val->image) {
					if (explode('.', $val->image)[1] == 'pdf') {
						$image = '<a href="' . $val->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>';
					} else {
						$image = '<a data-magnify="gallery" data-src="" data-caption="' . $val->note . '" data-group="a" href="' . $val->attachment() . '"><img src="' . $val->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a>';
					}
				}

				$button = '';

				if (in_array('3', session('bo_role')) || in_array('1', session('bo_role'))) {
					$button .= '<button type="button" class="btn bg-warning btn-sm" data-popup="tooltip" title="Edit" onclick="show(' . $val->id . ')"><i class="icon-pencil7"></i></button>
						<button type="button" class="btn bg-danger btn-sm" data-popup="tooltip" title="Delete" onclick="destroy(' . $val->id . ')"><i class="icon-trash-alt"></i></button>';
				}

				$cb = CashBank::where('code', 'BPC-' . $val->id)->first();

				if (in_array('4', session('bo_role')) || in_array('1', session('bo_role'))) {
					if ($cb || $val->cash_bank_reference > 0) {
						$button .= '<a class="btn btn-success btn-sm btn-icon rounded-round" data-popup="tooltip" title="Added to Journal & Cash Banks" href="' . url('admin/finance/cash_bank?mode_edit=true&id=' . ($val->cash_bank_reference ? $val->cash_bank_reference : $cb->id)) . '"><i class="icon-check"></i></a>';
					} else {
						$button .= '<a href="javascript:void(0);" class="btn btn-info btn-pindah btn-sm" data-nominal="' . number_format($val->nominal, 2, ',', '.') . '" data-id="' . $val->id . '" data-date="' . $val->date . '" data-item="' . $val->note . '" data-tipe="' . $val->type . '" data-coa="' . $val->coa_id . '" data-coaname="' . ($val->coa_id ? $val->coa->name : '') . '" data-branch="' . $val->branch . '" data-branchname="' . $val->branch() . '"><i class="icon-task"></i></a>';
					}
				} else {
					if ($cb || $val->cash_bank_reference > 0) {
						$button .= '<button class="btn btn-success btn-sm btn-icon rounded-round" data-popup="tooltip" title="Added to Journal & Cash Banks"><i class="icon-check"></i></button>';
					}
				}

				$type = '';

				if ($val->type == 'IN') {
					$type = '<button type="button" class="btn btn-success btn-labeled btn-labeled-left rounded-round"><b><i class="icon-box-add"></i></b> IN</button>';
				} elseif ($val->type == 'OUT') {
					$type = '<button type="button" class="btn btn-danger btn-labeled btn-labeled-left rounded-round"><b><i class="icon-box-remove"></i></b> OUT</button>';
				}

				$cashorbank = '';

				if ($val->cash_or_bank == 'CASH') {
					$cashorbank = '<button type="button" class="btn btn-success btn-labeled btn-labeled-left rounded-round"><b><i class="icon-wallet"></i></b> CASH</button>';
				} elseif ($val->cash_or_bank == 'BANK') {
					$cashorbank = '<button type="button" class="btn btn-info btn-labeled btn-labeled-left rounded-round"><b><i class="icon-piggy-bank"></i></b> BANK</button>';
				}

				$ref = '';

				if ($val->cash_bank_reference) {
					$cek = CashBank::find($val->cash_bank_reference);
					if (isset($cek->code)) {
						if (substr($cek->code, 0, 4) == 'PRP-') {
							$yolo = PurchaseRequestPayment::find(explode('-', $cek->code)[1]);

							if ($yolo) {
								if (isset($yolo->purchaseRequest->image)) {
									if (explode('.', $yolo->purchaseRequest->image)[1] == 'pdf') {
										$ref = '<a href="' . $yolo->purchaseRequest->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>';
									} else {
										$ref = '<a data-magnify="gallery" data-src="" data-caption="' . $yolo->purchaseRequest->item . '" data-group="a" href="' . $yolo->purchaseRequest->attachment() . '"><img src="' . $yolo->purchaseRequest->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a>';
									}
								}
							}

							if (isset($yolo->image) && $yolo->image !== '') {
								if (explode('.', $yolo->image)[1] == 'pdf') {
									$image = '<a href="' . $yolo->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>';
								} else {
									$image = '<a data-magnify="gallery" data-src="" data-caption="' . $yolo->note . '" data-group="a" href="' . $yolo->attachment() . '"><img src="' . $yolo->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a>';
								}
							}
						}
					}
				}

				$response['data'][] = [
					'<span class="pick">' . $val->id . '</span>',
					$val->user ? $val->user->name : '',
					$val->branch(),
					number_format($val->nominal, 2, ',', '.'),
					$type,
					date('d M Y', strtotime($val->date)),
					$val->note,
					$cashorbank,
					$val->coa ? $val->coa->name : 'Not set',
					$ref,
					$image,
					$button
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
			'nominal_bpc'	=> 'required',
			'type_bpc'		=> 'required',
			'type_cb'		=> 'required',
			'type_coa'		=> 'required',
			'note_bpc'		=> 'required',
			'date_bpc'		=> 'required'
		], [
			'nominal_bpc.required' 	=> 'Nominal cannot empty.',
			'type_bpc.required'    	=> 'Type cannot empty.',
			'type_cb.required'    	=> 'Casb or bank cannot empty.',
			'type_coa.required'    	=> 'Coa cannot empty.',
			'note_bpc.required'    	=> 'Note cannot empty.',
			'date_bpc.required' 	=> 'Date cannot empty.'
		]);

		if ($validation->fails()) {
			$response = [
				'status' => 422,
				'error'  => $validation->errors()
			];
		} else {

			if (CheckCutOff::check($request->branch_bpc, substr($request->date_bpc, 0, 7))) {

				if ($request->temp_bpc) {

					$bh = BalanceHistory::find($request->temp_bpc);

					if ($request->has('file')) {
						if (Storage::exists($bh->image)) {
							Storage::delete($bh->image);
						}
						$image = $request->file('file')->store('public/cashbank');
					} else {
						$image = $bh->image;
					}

					$cek = CashBank::find($bh->cash_bank_reference);

					if ($cek) {
						/* $cek->deleteDetail();
						$cek->deleteFile();
						$cek->delete(); */
						if ($bh->type == 'OUT') {

							$cekpr = PurchaseRequestPayment::find(intval(explode('-', $cek->code)[1]));
							
							if ($request->has('file')) {
								$cekpr->deleteFile();	
							}

							if ($cekpr) {
								if ($cek->lookable_type == 'purchase_request_payments') {
									$cekpr->update([
										'date_paid' => $request->date_bpc,
										'coa_id'	=> $request->type_coa,
										'nominal'	=> str_replace(',', '.', str_replace('.', '', $request->nominal_bpc)),
										'branch'	=> $request->branch_bpc,
										'image'	    => $request->file('file') ? $request->file('file')->store('public/project') : '',
									]);

									foreach ($cek->cashBankDetail->where('type', '2') as $row) {
										$row->update([
											'coa_id'	=> $request->type_coa,
											'branch'	=> $request->branch_bpc,
											'nominal'	=> str_replace(',', '.', str_replace('.', '', $request->nominal_bpc))
										]);
									}

									foreach ($cek->journalDetail->where('type', '2') as $row) {
										$row->update([
											'coa_id'			=> $request->type_coa,
											'branch'			=> $request->branch_bpc,
											'nominal'			=> str_replace(',', '.', str_replace('.', '', $request->nominal_bpc))
										]);
									}

									foreach ($cek->journalDetail as $row) {
										$row->update([
											'date_transaction'	=> $request->date_bpc,
										]);
									}

									$cek->update([
										'date'	=> $request->date_bpc
									]);
								}
							}
						}
					} else {
						$cekbpc = CashBank::where('code', 'BPC-' . $request->temp_bpc)->first();

						if ($cekbpc) {
							if ($bh->type == 'IN') {
								if ($cekbpc->lookable_type == 'project_pays') {
									ProjectPay::find($cekbpc->lookable_id)->update([
										'date'		=> $request->date_bpc,
										'nominal'	=> str_replace(',', '.', str_replace('.', '', $request->nominal_bpc)),
										'coa_id'	=> $request->type_coa
									]);
								} elseif ($cekbpc->lookable_type == 'project_main_payments') {
									ProjectMainPayment::find($cekbpc->lookable_id)->update([
										'date'		=> $request->date_bpc,
										'nominal'	=> str_replace(',', '.', str_replace('.', '', $request->nominal_bpc)),
										'coa_id'	=> $request->type_coa
									]);
								}

								foreach ($cekbpc->cashBankDetail->where('type', '1') as $row) {
									$row->update([
										'coa_id'	=> $request->type_coa,
										'branch'	=> $request->branch_bpc,
										'nominal'	=> str_replace(',', '.', str_replace('.', '', $request->nominal_bpc))
									]);
								}
								foreach ($cekbpc->journalDetail->where('type', '1') as $row) {
									$row->update([
										'date_transaction'	=> $request->date_bpc,
										'coa_id'	=> $request->type_coa,
										'branch'	=> $request->branch_bpc,
										'nominal'	=> str_replace(',', '.', str_replace('.', '', $request->nominal_bpc))
									]);
								}
							} elseif ($bh->type == 'OUT') {
							}
						}
					}

					$bh->user_id = session('bo_id');
					$bh->nominal = str_replace(',', '.', str_replace('.', '', $request->nominal_bpc));
					$bh->type = $request->type_bpc;
					$bh->cash_or_bank = $request->type_cb;
					$bh->coa_id = $request->type_coa;
					$bh->branch = $request->branch_bpc;
					$bh->note = $request->note_bpc;
					$bh->date = $request->date_bpc;
					$bh->image = $image;
					//$bh->cash_bank_reference = NULL;

					$bh->save();
				} else {
					$bh = BalanceHistory::create([
						'user_id' 		=> session('bo_id'),
						'nominal'		=> str_replace(',', '.', str_replace('.', '', $request->nominal_bpc)),
						'type'			=> $request->type_bpc,
						'cash_or_bank'	=> $request->type_cb,
						'coa_id'		=> $request->type_coa,
						'branch'		=> $request->branch_bpc,
						'note'			=> $request->note_bpc,
						'date'			=> $request->date_bpc,
						'image'			=> $request->file('file') ? $request->file('file')->store('public/cashbank') : ''
					]);

					if ($request->type_bpc == 'IN') {
					} elseif ($request->type_bpc == 'OUT') {
						/* $cb = CashBank::create([
							'user_id'     		=> session('bo_id'),
							'lookable_type'  	=> 'balance_histories',
							'lookable_id'		=> $bh->id,
							'code'        		=> 'BPC-'.$bh->id,
							'date'        		=> $request->date_bpc,
							'type'        		=> '2',
							'description' 		=> $request->note_bpc
						]);
						
						if($cb){
							CashBankDetail::create([
								'cash_bank_id' 	=> $cb->id,
								'coa_id'       	=> 48,
								'branch'		=> $request->branch_bpc,
								'type'       	=> '1',
								'nominal'      	=> str_replace(',','.',str_replace('.','',$request->nominal_bpc)),
								'note'         	=> $request->note_bpc
							]);
							
							Journal::insert([
								'date_transaction' => $request->date_bpc,
								'journalable_type' => 'cash_banks',
								'journalable_id'   => $cb->id,
								'coa_id'           => 48,
								'branch'		   => $request->branch_bpc,
								'type'	           => '1',
								'nominal'          => str_replace(',','.',str_replace('.','',$request->nominal_bpc)),
								'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
								'updated_at'       => date('Y-m-d H:i:s')
							]);
							
							CashBankDetail::create([
								'cash_bank_id' 	=> $cb->id,
								'coa_id'       	=> $request->type_coa,
								'branch'		=> $request->branch_bpc,
								'type'       	=> '2',
								'nominal'      	=> str_replace(',','.',str_replace('.','',$request->nominal_bpc)),
								'note'         	=> $request->note_bpc
							]);
							
							Journal::insert([
								'date_transaction' => $request->date_bpc,
								'journalable_type' => 'cash_banks',
								'journalable_id'   => $cb->id,
								'coa_id'           => $request->type_coa,
								'branch'		   => $request->branch_bpc,
								'type'	           => '2',
								'nominal'          => str_replace(',','.',str_replace('.','',$request->nominal_bpc)),
								'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
								'updated_at'       => date('Y-m-d H:i:s')
							]);
						} */
					}
				}

				activity()
					->performedOn(new BalanceHistory())
					->causedBy(session('bo_id'))
					->log('Add / edit Balance data');

				$response = [
					'status'  => 200,
					'message' => 'Data added successfully.'
				];
			} else {
				$response = [
					'status'  => 503,
					'message' => 'You cannot add/edit. The journal for this month was already closed.'
				];
			}
		}

		return response()->json($response);
	}

	public function createTransfer(Request $request)
	{
		$validation = Validator::make($request->all(), [
			'date_bpc_transfer'		=> 'required',
			'note_bpc_transfer'		=> 'required',
			'arr_type_cb_out'		=> 'required',
			'arr_nominal_cb_out'	=> 'required',
			'arr_coa_cb_out'		=> 'required',
			'arr_branch_cb_out'		=> 'required',
			'arr_type_cb_in'		=> 'required',
			'arr_nominal_cb_in'		=> 'required',
			'arr_coa_cb_in'			=> 'required',
			'arr_branch_cb_in'		=> 'required',
		], [
			'date_bpc_transfer.required' 	=> 'Nominal cannot empty.',
			'note_bpc_transfer.required'    => 'Note cannot empty.',
			'arr_type_cb_out.required'    	=> 'Type cannot empty.',
			'arr_nominal_cb_out.required'   => 'Nominal cannot empty.',
			'arr_coa_cb_out.required' 		=> 'Coa cannot empty.',
			'arr_branch_cb_out.required' 	=> 'Branch cannot empty.',
			'arr_type_cb_in.required'    	=> 'Type cannot empty.',
			'arr_nominal_cb_in.required'   	=> 'Nominal cannot empty.',
			'arr_coa_cb_in.required' 		=> 'Coa cannot empty.',
			'arr_branch_cb_in.required' 	=> 'Branch cannot empty.',
		]);

		if ($validation->fails()) {
			$response = [
				'status' => 422,
				'error'  => $validation->errors()
			];
		} else {

			if (CheckCutOff::check($request->arr_branch_cb_out[0], substr($request->date_bpc_transfer, 0, 7))) {

				foreach ($request->arr_type_cb_out as $key => $row) {
					$bh = BalanceHistory::create([
						'user_id' 		=> session('bo_id'),
						'nominal'		=> str_replace(',', '.', str_replace('.', '', $request->arr_nominal_cb_out[$key])),
						'type'			=> 'OUT',
						'cash_or_bank'	=> $row,
						'coa_id'		=> $request->arr_coa_cb_out[$key],
						'branch'		=> $request->arr_branch_cb_out[$key],
						'note'			=> $request->note_bpc_transfer,
						'date'			=> $request->date_bpc_transfer,
						'image'			=> $request->file('file_bpc_transfer') ? $request->file('file_bpc_transfer')->store('public/balance') : NULL
					]);

					$cb = CashBank::create([
						'user_id'     		=> session('bo_id'),
						'lookable_type'  	=> 'balance_histories',
						'lookable_id'		=> $bh->id,
						'code'        		=> 'BPC-' . $bh->id,
						'date'        		=> $request->date_bpc_transfer,
						'type'        		=> '2',
						'description' 		=> $request->note_bpc_transfer
					]);

					if ($cb) {
						CashBankDetail::create([
							'cash_bank_id' 	=> $cb->id,
							'coa_id'       	=> 22,
							'branch'		=> $request->arr_branch_cb_out[$key],
							'type'       	=> '1',
							'nominal'      	=> str_replace(',', '.', str_replace('.', '', $request->arr_nominal_cb_out[$key])),
							'note'         	=> $request->note_bpc_transfer
						]);

						Journal::insert([
							'date_transaction' => $request->date_bpc_transfer,
							'journalable_type' => 'cash_banks',
							'journalable_id'   => $cb->id,
							'coa_id'           => 22,
							'branch'		   => $request->arr_branch_cb_out[$key],
							'type'	           => '1',
							'nominal'          => str_replace(',', '.', str_replace('.', '', $request->arr_nominal_cb_out[$key])),
							'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
							'updated_at'       => date('Y-m-d H:i:s')
						]);

						CashBankDetail::create([
							'cash_bank_id' 	=> $cb->id,
							'coa_id'       	=> $request->arr_coa_cb_out[$key],
							'branch'		=> $request->arr_branch_cb_out[$key],
							'type'       	=> '2',
							'nominal'      	=> str_replace(',', '.', str_replace('.', '', $request->arr_nominal_cb_out[$key])),
							'note'         	=> $request->note_bpc_transfer
						]);

						Journal::insert([
							'date_transaction' => $request->date_bpc_transfer,
							'journalable_type' => 'cash_banks',
							'journalable_id'   => $cb->id,
							'coa_id'           => $request->arr_coa_cb_out[$key],
							'branch'		   => $request->arr_branch_cb_out[$key],
							'type'	           => '2',
							'nominal'          => str_replace(',', '.', str_replace('.', '', $request->arr_nominal_cb_out[$key])),
							'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
							'updated_at'       => date('Y-m-d H:i:s')
						]);
					}

					BalanceHistory::find($bh->id)->update([
						'cash_bank_reference'	=> $cb->id
					]);

					activity()
						->performedOn(new BalanceHistory())
						->causedBy(session('bo_id'))
						->withProperties($bh)
						->log('Add / edit Balance data');
				}

				foreach ($request->arr_type_cb_in as $key => $row) {
					$bh = BalanceHistory::create([
						'user_id' 		=> session('bo_id'),
						'nominal'		=> str_replace(',', '.', str_replace('.', '', $request->arr_nominal_cb_in[$key])),
						'type'			=> 'IN',
						'cash_or_bank'	=> $row,
						'coa_id'		=> $request->arr_coa_cb_in[$key],
						'branch'		=> $request->arr_branch_cb_in[$key],
						'note'			=> $request->note_bpc_transfer,
						'date'			=> $request->date_bpc_transfer,
						'image'			=> $request->file('file_bpc_transfer') ? $request->file('file_bpc_transfer')->store('public/balance') : NULL
					]);

					$cb = CashBank::create([
						'user_id'     		=> session('bo_id'),
						'lookable_type'  	=> 'balance_histories',
						'lookable_id'		=> $bh->id,
						'code'        		=> 'BPC-' . $bh->id,
						'date'        		=> $request->date_bpc_transfer,
						'type'        		=> '1',
						'description' 		=> $request->note_bpc_transfer
					]);

					if ($cb) {
						CashBankDetail::create([
							'cash_bank_id' 	=> $cb->id,
							'coa_id'       	=> $request->arr_coa_cb_in[$key],
							'branch'		=> $request->arr_branch_cb_in[$key],
							'type'       	=> '1',
							'nominal'      	=> str_replace(',', '.', str_replace('.', '', $request->arr_nominal_cb_in[$key])),
							'note'         	=> $request->note_bpc_transfer
						]);

						Journal::insert([
							'date_transaction' => $request->date_bpc_transfer,
							'journalable_type' => 'cash_banks',
							'journalable_id'   => $cb->id,
							'coa_id'           => $request->arr_coa_cb_in[$key],
							'branch'		   => $request->arr_branch_cb_in[$key],
							'type'	           => '1',
							'nominal'          => str_replace(',', '.', str_replace('.', '', $request->arr_nominal_cb_in[$key])),
							'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
							'updated_at'       => date('Y-m-d H:i:s')
						]);

						CashBankDetail::create([
							'cash_bank_id' 	=> $cb->id,
							'coa_id'       	=> 22,
							'branch'		=> $request->arr_branch_cb_in[$key],
							'type'       	=> '2',
							'nominal'      	=> str_replace(',', '.', str_replace('.', '', $request->arr_nominal_cb_in[$key])),
							'note'         	=> $request->note_bpc_transfer
						]);

						Journal::insert([
							'date_transaction' => $request->date_bpc_transfer,
							'journalable_type' => 'cash_banks',
							'journalable_id'   => $cb->id,
							'coa_id'           => 22,
							'branch'		   => $request->arr_branch_cb_in[$key],
							'type'	           => '2',
							'nominal'          => str_replace(',', '.', str_replace('.', '', $request->arr_nominal_cb_in[$key])),
							'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
							'updated_at'       => date('Y-m-d H:i:s')
						]);
					}

					BalanceHistory::find($bh->id)->update([
						'cash_bank_reference'	=> $cb->id
					]);

					activity()
						->performedOn(new BalanceHistory())
						->causedBy(session('bo_id'))
						->withProperties($bh)
						->log('Add / edit Balance data');
				}

				$response = [
					'status'  => 200,
					'message' => 'Data added successfully.'
				];
			} else {
				$response = [
					'status'  => 503,
					'message' => 'You cannot add/edit. The journal for this month was already closed.'
				];
			}
		}

		return response()->json($response);
	}


	public function createCb(Request $request)
	{
		$validation = Validator::make($request->all(), [
			'code'           => 'required|unique:cash_banks,code',
			'coa_detail'	 => 'required',
			'type_detail' 	 => 'required',
			'nominal_detail' => 'required',
			'date'           => 'required',
			'type'           => 'required',
			'description'    => 'required'
		], [
			'code.required'           => 'Code cannot be a empty.',
			'code.unique'             => 'Code already exists.',
			'coa_detail.required'     => 'Coa transaction cannot be a empty.',
			'type_detail.required' 	  => 'Type transaction cannot be a empty.',
			'nominal_detail.required' => 'Detail transaction cannot be a empty.',
			'date.required'           => 'Date cannot be empty.',
			'type.required'           => 'Please select a type.',
			'description.required'    => 'Description cannot be empty.'
		]);

		if ($validation->fails()) {
			$response = [
				'status' => 422,
				'error'  => $validation->errors()
			];
		} else {
			if (CheckCutOff::check($request->branch_detail[0], substr($request->date, 0, 7))) {

				$query = CashBank::create([
					'user_id'     			=> session('bo_id'),
					'request_date'			=> $request->request_date,
					'due_date'				=> $request->due_date,
					'code'        			=> $request->code,
					'date'        			=> $request->date,
					'type'        			=> $request->type,
					'description' 			=> $request->description
				]);

				$bg = BalanceHistory::find(explode('-', $request->code)[1]);

				if ($request->has('file')) {
					if (Storage::exists($bg->image)) {
						Storage::delete($bg->image);
					}
					$image = $request->file('file')->store('public/cashbank');
				} else {
					$image = $bg->image;
				}

				$bg->cash_bank_reference = $query->id;
				$bg->image = $image;
				$bg->save();

				if ($query) {
					foreach ($request->coa_detail as $key => $dd) {
						CashBankDetail::create([
							'cash_bank_id' 	=> $query->id,
							'coa_id'       	=> $dd,
							'branch'		=> $request->branch_detail[$key],
							'type'       	=> $request->type_detail[$key],
							'nominal'      	=> str_replace(',', '.', str_replace('.', '', $request->nominal_detail[$key])),
							'note'         	=> $request->note_detail[$key]
						]);

						Journal::insert([
							'date_transaction' => $request->date,
							'journalable_type' => 'cash_banks',
							'journalable_id'   => $query->id,
							'coa_id'           => $dd,
							'branch'		   => $request->branch_detail[$key],
							'type'	           => $request->type_detail[$key],
							'nominal'          => str_replace(',', '.', str_replace('.', '', $request->nominal_detail[$key])),
							'created_at'       => date('Y-m-d', strtotime($query->date)) . ' ' . date('H:i:s'),
							'updated_at'       => date('Y-m-d H:i:s')
						]);
					}

					activity()
						->performedOn(new CashBank())
						->causedBy(session('bo_id'))
						->withProperties($query)
						->log('Add accounting cash & bank data');

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
			} else {
				$response = [
					'status'  => 503,
					'message' => 'You cannot add/edit. The journal for this month was already closed.'
				];
			}
		}

		return response()->json($response);
	}

	public function destroy(Request $request)
	{

		$query = BalanceHistory::find($request->id);

		// if (CheckCutOff::check($query->branch, substr($query->date, 0, 7))) {

			$cek = CashBank::find($query->cash_bank_reference);

			if ($cek) {
				/* $response = [
					'status'  => 500,
					'message' => 'Data already in Cash & Bank. Please contact Accounting.'
				]; */
				
				if($query->type == 'OUT' && $cek->lookable_type  == 'purchase_request_payments'){
					$cek->lookable->delete();
				}

				if($query->type == 'IN' && $cek->lookable_type  == 'project_main_payments'){
					$query_project_main_payment = $cek->lookable;

					foreach($query_project_main_payment->projectPay as $row){
						$this->deletePayment($row->id);
					}

					$query_project_main_payment->deleteFile();
					$query_project_main_payment->delete();
				}
				
				$cek->deleteDetail();
				$cek->deleteFile();

				$cek->delete();
			}

			if ($query->image) {
				if (explode('/', $query->image)[1] == 'cashbank') {
					$query->deleteFile();
				}
			}

			if ($query->delete()) {
				activity()
					->performedOn(new BalanceHistory())
					->causedBy(session('bo_id'))
					->log('Delete the balance petty cash data');

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
		// } else {
		// 	$response = [
		// 		'status'  => 503,
		// 		'message' => 'You cannot add/edit. The journal for this month was already closed.'
		// 	];
		// }

		return response()->json($response);
	}

	public function show(Request $request)
	{
		$data = BalanceHistory::find($request->id);

		$data['nominal'] = number_format($data->nominal, 2, ',', '.');

		return response()->json($data);
	}

	public function showDetail(Request $request)
	{
		$startDate = $request->startDate;
		$endDate = $request->endDate;

		$totalBefore = 0;
		$totalOutPurchase = 0;
		$totalIn = 0;
		$totalOut = 0;

		$totalBeforeIn = BalanceHistory::where('coa_id', $request->coa)->where('type', 'IN')->where('branch', $request->branch)->whereDate('date', '<', $startDate)->sum('nominal');
		$totalBeforeOut = BalanceHistory::where('coa_id', $request->coa)->where('type', 'OUT')->where('branch', $request->branch)->whereDate('date', '<', $startDate)->sum('nominal');
		//$dataBeforePurchase = PurchaseRequest::where('coa_id',$request->coa)->where('branch',$request->branch)->whereRaw('DATE(date) < "'.$startDate.'"')->where('status','PAID')->sum('total_nominal');
		$rowBeforePurchase = PurchaseRequestPayment::where('coa_id', $request->coa)->where('branch', $request->branch)->whereRaw('DATE(date_paid) < "' . $startDate . '"')->get();

		$dataBeforePurchase = 0;

		foreach ($rowBeforePurchase as $row) {
			if (!$row->cekCB() && !$row->purchase_request_main_payment_id) {
				$dataBeforePurchase += $row->nominal;
			}
		}

		$totalBefore = $totalBeforeIn - $totalBeforeOut;

		$dataIn   = BalanceHistory::where('coa_id', $request->coa)->where('type', 'IN')->where('branch', $request->branch)->whereBetween('date', [$startDate, $endDate])->orderBy('date')->get();
		$dataOut   = BalanceHistory::where('coa_id', $request->coa)->where('type', 'OUT')->where('branch', $request->branch)->whereBetween('date', [$startDate, $endDate])->orderBy('date')->get();
		//$datapurchase = PurchaseRequest::where('coa_id',$request->coa)->where('branch',$request->branch)->whereRaw('DATE(date) >= "'.$startDate.'" AND DATE(date) <= "'.$endDate.'"')->orderBy('date')->where('status','PAID')->get();
		$datapurchase = PurchaseRequestPayment::where('coa_id', $request->coa)->where('branch', $request->branch)->whereRaw('DATE(date_paid) >= "' . $startDate . '" AND DATE(date_paid) <= "' . $endDate . '"')->orderBy('date_paid')->get();

		$string = '<div class="row">';

		$no = 1;

		$balance = $totalBefore;

		$string .= '
			<div class="col-md-12 p-3">
				<div class="table-responsive">
					<table class="table table-bordered table-striped w-100 table-hover">
						<thead class="bg-dark">
							<tr class="text-center">
								<th>No</th>
								<th>Note/Item</th>
								<th>Type</th>
								<th>Date</th>
								<th>Debit</th>
								<th>Kredit</th>
								<th>Balance</th>
							</tr>
						</thead>
						<tbody>';

		$string .= '<tr class="text-center">
					  <td class="align-middle">-</a></td>
					  <td class="align-middle">BALANCE BEFORE</td>
					  <td class="align-middle"></td>
					  <td class="align-middle">' . date('d M Y', strtotime($startDate)) . '</td>
					  <td class="text-right">-</td>
					  <td class="text-right">-</td>
					  <td class="text-right">' . number_format($balance, 2, ',', '.') . '</td>
				   </tr>';

		while (strtotime($startDate) <= strtotime($endDate)) {

			foreach ($dataIn as $row) {
				if ($row->date == $startDate) {
					$balance += $row->nominal;
					$string .= '<tr class="text-center">
					  <td class="align-middle">' . $no . '</a></td>
					  <td class="align-middle">' . $row->note . '</td>
					  <td class="align-middle"><button type="button" class="btn btn-success btn-labeled btn-labeled-left rounded-round"><b><i class="icon-box-add"></i></b> IN</button></td>
					  <td class="align-middle">' . date('d M Y', strtotime($startDate)) . '</td>
					  <td class="text-right">' . number_format($row->nominal, 2, ',', '.') . '</td>
					  <td class="text-right">-</td>
					  <td class="text-right">' . number_format($balance, 2, ',', '.') . '</td>
				   </tr>';
					$totalIn += $row->nominal;
					$no++;
				}
			}

			foreach ($dataOut as $row) {
				if ($row->date == $startDate) {
					$balance -= $row->nominal;
					$string .= '<tr class="text-center">
						  <td class="align-middle">' . $no . '</a></td>
						  <td class="align-middle">' . $row->note . '</td>
						  <td class="align-middle"><button type="button" class="btn btn-danger btn-labeled btn-labeled-left rounded-round"><b><i class="icon-box-remove"></i></b> OUT</button></td>
						  <td class="align-middle">' . date('d M Y', strtotime($startDate)) . '</td>
						  <td class="text-right">-</td>
						  <td class="text-right">' . number_format($row->nominal, 2, ',', '.') . '</td>
						  <td class="text-right">' . number_format($balance, 2, ',', '.') . '</td>
					   </tr>';
					$totalOut += $row->nominal;
					$no++;
				}
			}

			$startDate = date("Y-m-d", strtotime("+1 days", strtotime($startDate)));
		}

		$string .= '</tbody>
					<tfoot>
					<tr>
						<th class="text-right" colspan="4">Total</th>
						<th class="text-right">' . number_format($totalIn, 2, ',', '.') . '</th>
						<th class="text-right">' . number_format($totalOut, 2, ',', '.') . '</th>
						<th class="text-right"></th>
					</tr>
				  </tfoot>
				  </table>
				</div>
			</div>
		';

		$string .= '
			<div class="col-md-12 p-3">
				<div class="table-responsive">
					<table class="table table-bordered table-striped w-100 table-hover">
						<thead class="bg-dark">
							<tr class="text-center">
								<th colspan="5">Purchase Request Payment</th>
							</tr>
							<tr class="text-center">
								<th>No</th>
								<th>Note/Item</th>
								<th>Date</th>
								<th>Nominal</th>
								<th>Balance</th>
							</tr>
						</thead>
						<tbody>';

		$string .= '<tr class="text-center">
					  <td class="align-middle">-</a></td>
					  <td class="align-middle">BALANCE PURCHASE REQUEST PAYMENT BEFORE</td>
					  <td class="align-middle">' . date('d M Y', strtotime($request->startDate)) . '</td>
					  <td class="text-right">' . number_format($dataBeforePurchase, 2, ",", ".") . '</td>
					  <td class="text-right">' . number_format($balance - $dataBeforePurchase, 2, ',', '.') . '</td>
				   </tr>';

		$totalpurchase = 0;
		$no = 1;

		$startDate = $request->startDate;

		while (strtotime($startDate) <= strtotime($endDate)) {
			foreach ($datapurchase as $row) {
				if (!$row->cekCB() && $row->purchaseRequest->link_type !== 'project_purchases' && !$row->purchase_request_main_payment_id) {
					if ($row->date_paid == $startDate) {
						$balance -= $row->nominal;
						$totalpurchase += $row->nominal;

						$string .= '<tr class="text-center">
							  <td class="align-middle">' . $no . '</a></td>
							  <td class="align-middle">' . $row->purchaseRequest->title . ' ' . $row->purchaseRequest->item . '</td>
							  <td class="align-middle">' . date('d M Y', strtotime($row->date_paid)) . '</td>
							  <td class="text-right">' . number_format($row->nominal, 2, ',', '.') . '</td>
							  <td class="text-right">' . number_format($balance, 2, ',', '.') . '</td>
						   </tr>';
						$no++;
					}
				}
			}

			$startDate = date("Y-m-d", strtotime("+1 days", strtotime($startDate)));
		}

		$string .= '</tbody>
					<tfoot>
					<tr>
						<th class="text-right" colspan="3">Total</th>
						<th class="text-right">' . number_format($totalpurchase, 2, ',', '.') . '</th>
						<th class="text-right"></th>
					</tr>
				  </tfoot>
				  </table>
				</div>
			</div>
		';

		return response()->json([
			'status' => 200,
			'content' => $string
		]);
	}

	public function showApproved(Request $request)
	{
		$data = BalanceHistory::all();

		$html = '<div class="table-responsive">
					<table class="table table-bordered table-striped w-100 table-hover" id="table-balance-history">
						<thead class="bg-dark">
							<tr class="text-center">
								<th>No</th>
								<th>Branch</th>
								<th>Nominal</th>
								<th>Type</th>
								<th>Date</th>
								<th>Note</th>
								<th>Ref</th>
								<th>Proof</th>
								<th>#</th>
							</tr>
						</thead>
						<tbody>';

		foreach ($data as $key => $row) {

			$ref = '';
			$image = '';

			if ($row->image) {
				if (explode('.', $row->image)[1] == 'pdf') {
					$image = '<a href="' . $row->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>';
				} else {
					$image = '<a data-magnify="gallery" data-src="" data-caption="' . $row->note . '" data-group="a" href="' . $row->attachment() . '"><img src="' . $row->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a>';
				}
			}

			if ($row->cash_bank_reference) {
				$cek = CashBank::find($row->cash_bank_reference);
				if (isset($cek->code)) {
					if (substr($cek->code, 0, 4) == 'PRP-') {
						$yolo = PurchaseRequestPayment::find(explode('-', $cek->code)[1]);

						if ($yolo) {
							if (isset($yolo->purchaseRequest->image)) {
								if (explode('.', $yolo->purchaseRequest->image)[1] == 'pdf') {
									$ref = '<a href="' . $yolo->purchaseRequest->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>';
								} else {
									$ref = '<a data-magnify="gallery" data-src="" data-caption="' . $yolo->purchaseRequest->item . '" data-group="a" href="' . $yolo->purchaseRequest->attachment() . '"><img src="' . $yolo->purchaseRequest->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a>';
								}
							}
						}

						if (isset($yolo->image) && $yolo->image !== '') {
							if (explode('.', $yolo->image)[1] == 'pdf') {
								$image = '<a href="' . $yolo->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>';
							} else {
								$image = '<a data-magnify="gallery" data-src="" data-caption="' . $yolo->note . '" data-group="a" href="' . $yolo->attachment() . '"><img src="' . $yolo->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a>';
							}
						}
					}
				}
			}

			$cb = CashBank::where('code', 'BPC-' . $row->id)->first();

			if (!$cb && ($row->cash_bank_reference == 0 && $row->cash_bank_reference == NULL)) {

				if ($row->type == 'IN') {
					$type = '<button type="button" class="btn btn-success btn-labeled btn-labeled-left rounded-round"><b><i class="icon-box-add"></i></b> IN</button>';
				} elseif ($row->type == 'OUT') {
					$type = '<button type="button" class="btn btn-danger btn-labeled btn-labeled-left rounded-round"><b><i class="icon-box-remove"></i></b> OUT</button>';
				}

				$button = '<a href="javascript:void(0);" class="btn btn-info btn-pindah btn-sm" onclick="getPindah(`' . number_format($row->nominal, 2, ",", ".") . '`,' . $row->id . ',`' . $row->date . '`,`' . $row->note . '`,`' . $row->type . '`,' . ($row->coa_id ? $row->coa_id : "``") . ',`' . ($row->coa_id ? $row->coa->name : "") . '`,`' . $row->branch . '`,`' . $row->branch() . '`)"><i class="icon-task"></i></a>';

				$html .= '
					<tr class="text-center">
						<th>' . $row->id . '</th>
						<th>' . $row->branch() . '</th>
						<th>' . number_format($row->nominal, 2, ',', '.') . '</th>
						<th>' . $type . '</th>
						<th>' . date('d M Y', strtotime($row->date)) . '</th>
						<th>' . $row->note . '</th>
						<th>' . $ref . '</th>
						<th>' . $image . '</th>
						<th>' . $button . '</th>
					</tr>
				';
			}
		}

		$html .= '
					</tbody>
				</table>
			</div>
		';

		return response()->json([
			'status' => 200,
			'content' => $html
		]);
	}

	public function print(Request $request)
	{

		$start = $request->filter_start_date;
		$finish = $request->filter_finish_date;
		$user = $request->filter_user_id ? $request->filter_user_id : '';
		$type = $request->filter_type ? $request->filter_type : '';
		$branch = $request->filter_branch ? $request->filter_branch : '';
		$arrId = explode(',', $request->filter_temp);

		$result = BalanceHistory::whereIn('id', $arrId)->where(function ($query) use ($start, $finish, $user, $type, $branch) {
			if ($start && $finish) {
				$query->whereDate('date', '>=', $start)
					->whereDate('date', '<=', $finish);
			}

			if ($user) {
				$query->where('user_id', $user);
			}

			if ($branch) {
				$query->where('branch', $branch);
			}

			if ($type) {
				$query->where('type', $type);
			}
		})->orderBy('date')
			->get();

		$pdf = PDF::loadView(
			'admin.pdf.report.finance.balance_cash_bank',
			[
				'start_date'	=> $start,
				'finish_date'	=> $finish,
				'data'			=> $result,
				'type'			=> $type,
				'title'			=> 'TJS Report Balance Cash & Bank',
			],
			[],
			[
				'format' => 'A4-P',
				'orientation' => 'P'
			]
		);

		return $pdf->stream('TJS Report Balance Cash & Bank.pdf');
	}

	public function getBalanceCashBank(Request $request)
	{

		$branch = $request->branch;
		$coa_id = $request->coa_id;

		$totalIn = BalanceHistory::where('type', 'IN')->where('coa_id', $coa_id)->where('branch', $branch)->sum('nominal');
		$totalOut = BalanceHistory::where('type', 'OUT')->where('coa_id', $coa_id)->where('branch', $branch)->sum('nominal');

		return response()->json([
			'nominal'	=> number_format($totalIn - $totalOut, 2, ',', '.'),
		]);
	}
}
