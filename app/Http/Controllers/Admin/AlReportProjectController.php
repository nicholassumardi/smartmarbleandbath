<?php

namespace App\Http\Controllers\Admin;

use App\Models\AlProject;
use App\Models\AlChecklistProject;
use App\Models\AlChecklist;
use App\Models\AlSph;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AlReportProjectController extends Controller
{
    public function index(Request $request)
    {
		$filter_year = $request->filter_year ? $request->filter_year : date('Y');
		
		$alproject = AlProject::whereRaw("YEAR(date) = '$filter_year'")->get();
		$totalchecklist = AlChecklist::count();

        $data   = [
            'title'       		=> 'Al Laporan Proyek',
            'filter_year'      	=> $filter_year,
			'al_project'		=> $alproject,
			'total_checklist'	=> $totalchecklist,
            'content'     		=> 'admin.al.report.proyek'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function print(Request $request){
		$year = $request->year;
		
		$alproject = AlProject::whereRaw("YEAR(date) = '$year'")->get();
		$totalchecklist = AlChecklist::count();
		
		return view('admin.al.print.proyek', [
			'year'			=> $year,
			'data' 			=> $alproject,
			'checklist'		=> $totalchecklist
		]);
	}
}