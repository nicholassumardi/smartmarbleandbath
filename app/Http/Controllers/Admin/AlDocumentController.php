<?php

namespace App\Http\Controllers\Admin;

use App\Models\AlDocument;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AlDocumentController extends Controller
{
    public function index()
    {
        $data = [
            'title'   		=> 'AL Master Dokumen',
			'document'		=> AlDocument::all(),
            'content' 		=> 'admin.al.master_data.dokumen'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function create(Request $request)
    {

		$data = [
			'title'    		=> 'Tambah Baru Dokumen',
			'content'  		=> 'admin.al.master_data.dokumen_tambah'
		];

		return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function save(Request $request){
		$validation = Validator::make($request->all(), [
			'name' 	         			=> 'required',
			'content'		            => 'required'
		], [
			'name.required'          		=> 'Name cannot be empty.',
			'content.required'              => 'Content cannot be empty.'
		]);

		if($validation->fails()) {
			$response = [
				'status' => 422,
				'error'  => $validation->errors()
			];
		} else {
			if($request->temp){
				$query = AlDocument::find($request->temp)->update([
					'user_id'			=> session('bo_id'),
					'document_name'		=> $request->name,
					'content'			=> $request->content
				]);
			}else{
				$query = AlDocument::create([
					'user_id'			=> session('bo_id'),
					'document_name'		=> $request->name,
					'content'			=> $request->content,
					'status'			=> '0'
				]);
			}
			

            if($query) {
                activity()
                    ->performedOn(new AlDocument())
                    ->causedBy(session('bo_id'))
                    ->withProperties($query)
                    ->log('Add/edit kelengkapan data AL');

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
	
	public function print(Request $request, $id){
		$data = AlDocument::find($id);
		
		return view('admin.al.print.document', ['data' => $data]);
	}
	
	public function edit(Request $request, $id){
		$document = AlDocument::find($id);
		
		$data = [
            'title'   		=> 'AL Edit Master Data Dokumen',
			'document'		=> $document,
            'content' 		=> 'admin.al.master_data.dokumen_edit'
        ];

        return view('admin.layouts.index', ['data' => $data]);
	}
	
	public function ckeditorUpload(Request $request)
    {
		$path_url = 'storage/al_ckeditor' . Auth::id();

		if($request->has('upload')){
			$originName = $request->file('upload')->getClientOriginalName();
			$fileName = pathinfo($originName, PATHINFO_FILENAME);
			$extension = $request->file('upload')->getClientOriginalExtension();
			$fileName = Str::slug($fileName) . '_' . time() . '.' . $extension;
			$request->file('upload')->move(public_path($path_url), $fileName);
			$url = asset($path_url . '/' . $fileName);
		}

		return response()->json(['url' => $url]);
	}
	
	public function destroy(Request $request){
		$document = AlDocument::find($request->id);
		
		$document->alDocumentProject()->delete();
		
		if($document->delete()){
			$response = [
                'status' 	=> 200,
                'message'  	=> 'Data deleted successfully.'
            ];
		}else{
			$response = [
                'status' 	=> 422,
                'message'  	=> 'Ups Error.'
            ];
		}
		
		return response()->json($response);
	}
}