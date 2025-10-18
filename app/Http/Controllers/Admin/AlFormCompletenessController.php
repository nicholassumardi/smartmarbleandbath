<?php

namespace App\Http\Controllers\Admin;

use App\Models\AlProject;
use App\Models\AlDocument;
use App\Models\AlDocumentProject;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AlFormCompletenessController extends Controller
{
    public function index()
    {
        $data = [
            'title'   		=> 'AL Kelengkapan Data',
			'proyek' 		=> AlProject::all(),
			'document'		=> AlDocument::all(),
            'content' 		=> 'admin.al.kelengkapan_data'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function create(Request $request)
    {

		$data = [
			'title'    		=> 'Tambah Baru Kelengkapan Data',
			'proyek' 		=> AlProject::all(),
			'document'		=> AlDocument::all(),
			'content'  		=> 'admin.al.kelengkapan_data_tambah'
		];

		return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function addDocument(Request $request){
		if($request->value == '0'){
			$query = AlDocumentProject::where('al_project_id',$request->project)->where('al_document_id',$request->doc)->first()->delete();
			
			if($query) {
				activity()
					->performedOn(new AlDocumentProject())
					->causedBy(session('bo_id'))
					->withProperties($query)
					->log('Delete kelengkapan data AL');

				$response = [
					'status'  => 200,
					'message' => 'Data deleted successfully.'
				];
			} else {
				$response = [
					'status'  => 500,
					'message' => 'Data failed to add.'
				];
			}
			
		}elseif($request->value == '1'){
			$query = AlDocumentProject::create([
				'user_id'			=> session('bo_id'),
				'al_project_id'		=> $request->project,
				'al_document_id'	=> $request->doc
			]);
			
			if($query) {
				activity()
					->performedOn(new AlDocumentProject())
					->causedBy(session('bo_id'))
					->withProperties($query)
					->log('Add kelengkapan data AL');

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
		$data = AlProject::find($id);
		
		return view('admin.al.print.document_project', ['proyek' => $data]);
	}
	
	public function edit(Request $request, $id){
		$document = AlDocument::find($id);
		
		$data = [
            'title'   		=> 'AL Kelengkapan Data',
			'customer' 		=> AlCustomer::all(),
			'document'		=> $document,
            'content' 		=> 'admin.al.kelengkapan_data_edit'
        ];

        return view('admin.layouts.index', ['data' => $data]);
	}
	
	public function destroy(Request $request){
		$document = AlDocument::find($request->id);
		
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