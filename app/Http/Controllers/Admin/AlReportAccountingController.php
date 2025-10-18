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

class AlReportAccountingController extends Controller
{
    public function index()
    {
        $data = [
            'title'   		=> 'AL Laporan Data',
            'content' 		=> 'admin.al.report.accounting'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function getReport(Request $request){
		$data = [];
		
		$startDate = $request->startMonth.'-01';
		$endDate = date("Y-m-t", strtotime($request->endMonth));
		
		$html = '';
		
		if($request->mode == '1'){
			
			$alproject = AlProject::whereRaw("date >= '$startDate' AND date <= '$endDate'")->get();
			
			$html .= '
					<h1>Laporan Rekap Profit Periode '.date('F Y',strtotime($request->startMonth)).' s/d '.date('F Y',strtotime($request->endMonth)).'</h1>
					<div class="table-responsive">	
						<table id="datatable_serverside" class="table table-bordered table-striped w-100">
						  <thead class="bg-dark sidebar-sticky">
							<tr class="text-center">
								<th width="1%">No</th>
								<th>Proyek</th>
								<th>Pemasukan</th>
								<th>Pengeluaran</th>
								<th>Profit</th>
							</tr>
						  </thead>
						  <tbody>
			';
			
			$no = 1;
			foreach($alproject as $rowproject){
				
				$html .= '
					<tr>
						<td>'.$no.'</td>
						<td>'.$rowproject->code.' - '.$rowproject->name.'</td>
						<td class="text-right">Rp '.number_format($rowproject->totalIncome(),0,",",".").'</td>
						<td class="text-right">Rp '.number_format($rowproject->totalExpense(),0,",",".").'</td>
						<td class="text-right">Rp '.number_format($rowproject->totalIncome() - $rowproject->totalExpense(),0,",",".").'</td>
					</tr>
				';
				
				$no++;
			}
			
			$html .= '
							</tbody>
						</table>
					</div>
			';
			
		}elseif($request->mode == '2'){
			
			$alproject = AlProject::whereRaw("date >= '$startDate' AND date <= '$endDate'")->get();
			
			$html .= '
					<h1>Laporan Rekap Profit & RAB Periode '.date('F Y',strtotime($request->startMonth)).' s/d '.date('F Y',strtotime($request->endMonth)).'</h1>
					<div class="table-responsive">	
						<table id="datatable_serverside" class="table table-bordered table-striped w-100">
						  <thead class="bg-dark sidebar-sticky">
							<tr class="text-center">
								<th width="1%" rowspan="2">No</th>
								<th rowspan="2">Proyek</th>
								<th colspan="3">RAB</th>
								<th colspan="3">Real</th>
							</tr>
							<tr class="text-center">
								<th>Pemasukan</th>
								<th>Pengeluaran</th>
								<th>Profit</th>
								<th>Pemasukan</th>
								<th>Pengeluaran</th>
								<th>Profit</th>
							</tr>
						  </thead>
						  <tbody>
			';
			
			$no = 1;
			foreach($alproject as $rowproject){
				
				$html .= '
					<tr>
						<td>'.$no.'</td>
						<td>'.$rowproject->code.' - '.$rowproject->name.'</td>
						<td class="text-right">Rp '.number_format($rowproject->totalBudgetingIncome(),0,",",".").'</td>
						<td class="text-right">Rp '.number_format($rowproject->totalBudgetingExpense(),0,",",".").'</td>
						<td class="text-right">Rp '.number_format($rowproject->totalBudgetingIncome() - $rowproject->totalBudgetingExpense(),0,",",".").'</td>
						<td class="text-right">Rp '.number_format($rowproject->totalIncome(),0,",",".").'</td>
						<td class="text-right">Rp '.number_format($rowproject->totalExpense(),0,",",".").'</td>
						<td class="text-right">Rp '.number_format($rowproject->totalIncome() - $rowproject->totalExpense(),0,",",".").'</td>
					</tr>
				';
				
				$no++;
			}
			
			$html .= '
							</tbody>
						</table>
					</div>
			';
			
		}elseif($request->mode == '3'){
			$alproject = AlProject::whereRaw("date >= '$startDate' AND date <= '$endDate'")->get();
			$totalchecklist = AlChecklist::count();
			
			$html .= '
					<h1>Laporan Rekap Progres Proyek Periode '.date('F Y',strtotime($request->startMonth)).' s/d '.date('F Y',strtotime($request->endMonth)).'</h1>
					<h5><i>Prosentase progres didapatkan dari checklist setiap proyek dibagi dengan total keseluruhan checklist yang ada.</i></h5>
					<div class="table-responsive">	
						<table id="datatable_serverside" class="table table-bordered table-striped w-100">
						  <thead class="bg-dark sidebar-sticky">
							<tr class="text-center">
								<th width="1%">No</th>
								<th width="40%">Proyek</th>
								<th>Progres</th>
								<th width="10%">Checklist</th>
							</tr>
						  </thead>
						  <tbody>
			';
			
			$no = 1;
			foreach($alproject as $rowproject){
				
				$html .= '
					<tr>
						<td>'.$no.'</td>
						<td>'.$rowproject->code.' - '.$rowproject->name.' CUST. '.$rowproject->alCustomer->name.'</td>
						<td class="text-center">
							<div class="progress rounded-round">
								<div class="progress-bar progress-bar-striped progress-bar-animated bg-success" style="width: '.$rowproject->getPercentComplete().'%">
									<span style="position: absolute;margin-left:45%;">'.$rowproject->getPercentComplete().'% Complete</span>
								</div>
							</div>
						</td>
						<td class="text-center">
							'.count($rowproject->alChecklistProject).' / '.$totalchecklist.'
						</td>
					</tr>
				';
				
				$no++;
			}
			
			$html .= '
							</tbody>
						</table>
					</div>
			';
		}
		
		return response()->json([
			'status'	=> 200,
			'content'	=> $html
		]);
	}
}