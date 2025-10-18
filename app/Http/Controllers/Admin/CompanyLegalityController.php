<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\CompanyLegality;
use App\Models\CompanyLegalityEmail;
use App\Models\CompanyLegalityCategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use File;
use ZipArchive;
use Config;
use Illuminate\Support\Facades\Mail;

class CompanyLegalityController extends Controller
{

	public function index()
	{
		$data = [
			'category'		=> CompanyLegalityCategory::whereHas('companyLegality')->get(),
			'datacategory'	=> CompanyLegalityCategory::all(),
			'unmatch'		=> CompanyLegality::doesntHave('companyLegalityCategory')->get(),
			'title'   		=> 'Company Legality Docs',
			'content' 		=> 'admin.legal_docs.company'
		];

		return view('admin.layouts.index', ['data' => $data]);
	}

	public function addFiles(Request $request)
	{

		$custom_file_name = strtolower(Str::random(10) . '_' . str_replace(' ', '_', $request->file('file')->getClientOriginalName()));

		// $query = CompanyLegality::create([
		// 	'user_id'						=> session('bo_id'),
		// 	'company_legality_category_id'	=> $request->id,
		// 	'file_name'						=> $request->file('file')->getClientOriginalName(),
		// 	'storage_name'					=> $request->file('file')->storeAs('public/company_legality', $custom_file_name)
		// ]);

		return response()->json([
			'status'		=> 200,
			'message'		=> 'You have successfully upload the file.',
			'category_id'	=> $request->category_id,
			'data'			=> $request->all(),
		]);
	}

	public function deleteFile(Request $request)
	{

		$data = CompanyLegality::find($request->id);

		$data->deleteFile();

		$data->delete();

		if ($data) {
			return response()->json([
				'status'	=> 200,
				'message'	=> 'File successfully deleted.'
			]);
		} else {
			return response()->json([
				'status'	=> 422,
				'message'	=> 'File not found.'
			]);
		}
	}

	public function deleteFiles(Request $request)
	{

		foreach ($request->arrid as $row) {

			$data = CompanyLegality::find($row);

			$data->deleteFile();

			$data->delete();
		}

		return response()->json([
			'status'	=> 200,
			'message'	=> 'File successfully deleted.'
		]);
	}

	public function downloadFile($id)
	{
		$file = CompanyLegality::find($id);

		if (!$file) {
			abort(404);
		}

		$pathToFile = storage_path('app/' . $file->storage_name);
		return response()->download($pathToFile);
	}

	public function downloadFiles(Request $request)
	{
		if ($request->has('arrid')) {
			$zip      = new ZipArchive;

			$fileName = 'pt_perwira_tamaraya_abadi_legal_docs.zip';

			if (file_exists(public_path($fileName))) {
				unlink(public_path($fileName));
			}

			foreach ($request->arrid as $id) {
				$file = CompanyLegality::find($id);
				if ($zip->open(public_path($fileName), ZipArchive::CREATE) === TRUE) {
					$relativeName = basename(storage_path('app/' . $file->storage_name));
					$zip->addFile(storage_path('app/' . $file->storage_name), $relativeName);

					$zip->close();
				}
			}

			return response()->download(public_path($fileName));
		}
	}

	public function sendMail(Request $request)
	{

		$validation = Validator::make($request->all(), [
			'email'   			=> 'required',
			'subject' 			=> 'required',
			'content' 			=> 'required',
		], [
			'email.required'   			=> 'Email cannot be empty.',
			'subject.required'   		=> 'Subject cannot be empty.',
			'content.required'    		=> 'Content cannot be empty.',
		]);

		if ($validation->fails()) {
			$response = [
				'status' => 422,
				'error'  => $validation->errors()
			];
		} else {

			$info = [
				'name'    => '',
				'email'   => $request->email,
				'link'    => '#',
				'view'    => 'al',
				'subject' => $request->subject,
				'content' => $request->content
			];

			$attachment = [];
			$arrFileName = [];

			foreach ($request->file_id as $row) {
				$attachment[] = CompanyLegality::find($row)->storage_name;
				$arrFileName[] = CompanyLegality::find($row)->file_name;
			}

			CompanyLegalityEmail::create([
				'user_id'		=> session('bo_id'),
				'email'			=> $request->email,
				'subject'		=> $request->subject,
				'content'		=> $request->content,
				'attachments'	=> implode(',', $arrFileName)
			]);

			Mail::send('emails.' . $info['view'], $info, function ($mail) use ($info, $attachment) {
				$mail->to($info['email'], $info['name']);
				$mail->subject($info['subject']);
				$mail->from(config('mail.mailers.smtp.username'), 'Smart Marble And Bath');
				foreach ($attachment as $row) {
					$mail->attach(storage_path('app/' . $row));
				}
			});

			$response = [
				'status' 	=> 200,
				'message'  	=> 'Email successfully sent.'
			];
		}

		return response()->json($response);
	}

	public function destroyCategory(Request $request)
	{

		$data = CompanyLegalityCategory::find($request->id);

		if ($data) {
			if (count($data->companyLegality) > 0) {
				return response()->json([
					'status'	=> 500,
					'message'	=> 'Category has file(s) in it, please delete the file(s) first.'
				]);
			} else {
				$data->delete();
			}
		}

		$return = CompanyLegalityCategory::all();

		return response()->json([
			'status'	=> 200,
			'message'	=> 'Category successfully deleted.',
			'data'		=> $return
		]);
	}

	public function createCategory(Request $request)
	{

		$validation = Validator::make($request->all(), [
			'category_name'   				=> 'required',
		], [
			'category_name.required'   		=> 'Category name cannot be empty.',
		]);

		if ($validation->fails()) {
			$response = [
				'status' => 422,
				'error'  => $validation->errors()
			];
		} else {

			$query = CompanyLegalityCategory::create([
				'name'		=> $request->category_name,
			]);

			$response = [
				'status' 	=> 200,
				'message'  	=> 'Email successfully sent.',
				'data'		=> $query
			];
		}

		return response()->json($response);
	}

	public function changeCategory(Request $request)
	{

		$validation = Validator::make($request->all(), [
			'change_category_id'   			=> 'required',
		], [
			'change_category_id.required'   => 'Category name cannot be empty.',
		]);

		if ($validation->fails()) {
			$response = [
				'status' => 422,
				'error'  => $validation->errors()
			];
		} else {

			foreach ($request->file_id_change as $row) {
				CompanyLegality::find($row)->update([
					'company_legality_category_id'	=> $request->change_category_id
				]);
			}

			$response = [
				'status' 	=> 200,
				'message'  	=> 'Data successfully changed.'
			];
		}

		return response()->json($response);
	}

	public function datatable(Request $request)
	{
		$column = [
			'id',
			'name',
		];

		$start  = $request->start;
		$length = $request->length;
		$order  = $column[$request->input('order.0.column')];
		$dir    = $request->input('order.0.dir');
		$search = $request->input('search.value');

		$total_data = CompanyLegalityCategory::count();

		$query_data = CompanyLegalityCategory::where(function ($query) use ($search, $request) {
			if ($search) {
				$query->where('name', 'like', "%$search%");
			}
		})
			->offset($start)
			->limit($length)
			->orderBy($order, $dir)
			->get();

		$total_filtered = CompanyLegalityCategory::where(function ($query) use ($search) {
			if ($search) {
				$query->where('name', 'like', "%$search%");
			}
		})
			->count();

		$response['data'] = [];
		if ($query_data <> FALSE) {
			$nomor = $start + 1;
			foreach ($query_data as $val) {
				$response['data'][] = [
					$nomor,
					$val->name,
					'<button type="button" class="btn bg-danger btn-sm" data-popup="tooltip" title="Hapus" onclick="destroy(' . $val->id . ')"><i class="icon-trash-alt"></i></button>'
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

	public function getHistoryEmail(Request $request)
	{
		$data = CompanyLegalityEmail::all();

		return response()->json([
			'status' 	=> 200,
			'data'  	=> $data
		]);
	}

	public function find(Request $request)
	{
		$search = $request->search;

		$data = CompanyLegality::where('file_name', 'like', "%$search%")->get();

		$html = '';
		$arrRow = [];


		if (count($data) > 0) {
			$html .= '<div class="row">';

			foreach ($data as $key => $row) {
				$html .= '
					<div class="col-sm-6 col-xl-3">
						<div class="card" data-id="' . $row->id . '" data-name="' . $row->file_name . '">
							<div class="card-img-actions mx-1 mt-1" onclick="chooseData(this)">
								<span class="badge badge-pill bg-danger ml-auto ml-md-0">' . ($key + 1) . '</span>
								' . ($row->extension() == 'pdf' ? '<canvas id="the-canvas-find' . $row->id . '"></canvas>' : '<div style="background:white;height:auto;">
										<img class="card-img img-fluid" src="' . $row->attachment() . '" alt="' . $row->file_name . '"></div>') .
					'<div class="card-img-actions-overlay card-img">
									<a href="' . asset(Storage::url($row->storage_name)) . '" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round" data-popup="lightbox" rel="group">
										<i class="icon-search4"></i>
									</a>
									<a href="' . url('admin/legal_docs/company/download/' . $row->id) . '" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round ml-2" target="_blank">
										<i class="icon-download"></i>
									</a>
									<a href="javascript:void(0);" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round ml-2" onclick="deleteFile(' . $row->id . ')">
										<i class="icon-bin"></i>
									</a>
								</div>
							</div>

							<div class="card-body" onclick="chooseData(this)">
								<div class="d-flex align-items-start flex-nowrap">
									<div>
										<div class="font-weight-semibold mr-2">' . $row->file_name . '</div>
										<span class="font-size-sm text-muted">Size: ' . number_format(Storage::disk('local')->size($row->storage_name) / 1024, 0, ',', '.') . ' Kb</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				';

				/* $row['attachment'] = $row->attachment();
				$arrRow[] = $row; */
			}

			$html .= '</div>';
		}


		return response()->json([
			'status' 	=> 200,
			'data'  	=> $html,
			//'row'		=> $arrRow
		]);
	}
}
