<?php

namespace App\Http\Controllers\Admin;

use App\Models\ProjectDelivery;
use App\Models\ProjectSaleReturn;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;


class ReportProjectSalesChartController extends Controller {
    
    public function index() 
    {
		$data = [
			'title'   => 'Project Sales Chart',
			'content' => 'admin.report.project.project_sales_chart'
		];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function generate(Request $request){
		
		$data = [];
		
		$startmonth = $request->startMonth;
		$endmonth = $request->endMonth;
		
		if($request->method == '1'){
			
			$month = strtotime($startmonth);
			$end = strtotime($endmonth);
			
			while($month <= $end)
			{
				$delivery = ProjectDelivery::whereHas('projectSale', function($query) use ($request) {
						$query->whereHas('sales', function($query) use ($request){
							if($request->branch){
								$query->where('branch',$request->branch);
							}
						});
					})
					->whereRaw('received_date IS NOT NULL AND received_date LIKE "'.date('Y-m', $month).'%"')->get();
				
				$totaldelivery = 0;
				
				foreach($delivery as $row){
					$totaldelivery += round($row->getTotalRawPlusService(),2);
				}
				
				$totalreturn = 0;
				
				$return = ProjectSaleReturn::whereHas('projectSale', function($query) use ($request) {
						$query->whereHas('sales', function($query) use ($request){
							if($request->branch){
								$query->where('branch',$request->branch);
							}
						});
					})
					->whereRaw('DATE(created_at) LIKE "'.date('Y-m', $month).'%"')->get();
					
				foreach($return as $row){
					if(date('Y-m-d',strtotime($row->projectSale->created_at)) < '2022-04-01'){
						$ppnpembagi = 1.1;
					}else{
						$ppnpembagi = 1.11;
					}
					
					
					if($row->project->ppn == '1'){
						$totalreturn += $row->getTotal() / $ppnpembagi;
					}else{
						$totalreturn += $row->getTotal();
					}
				}
				
				$data[] = [
					'month' => date('M y',$month),
					'sales'	=> round($totaldelivery - $totalreturn)
				];
				
				$month = strtotime("+1 month", $month);
			}
			
			$title = 'All Sales';
		}else{
			
			$delivery = ProjectDelivery::whereHas('projectSale', function($query) use ($request) {
						$query->whereHas('sales', function($query) use ($request){
							if($request->branch){
								$query->where('branch',$request->branch);
							}
						});
					})
					->whereRaw('received_date IS NOT NULL AND received_date BETWEEN "'.$startmonth.'-01" AND "'.date('Y-m-t', strtotime($endmonth)).'"')->get();
					
			$arrsales = [];
			
			foreach($delivery as $row){
				$arrsales[] = $row->projectSale->sales_id;
			}
			
			$arrsales = array_unique($arrsales);
			
			foreach($arrsales as $idsales){
				$totaldelivery = 0;
				
				$salesdelivery = ProjectDelivery::whereHas('projectSale', function($query) use ($idsales) {
						$query->where('sales_id',$idsales);
					})
					->whereRaw('received_date IS NOT NULL AND received_date BETWEEN "'.$startmonth.'-01" AND "'.date('Y-m-t', strtotime($endmonth)).'"')->get();
					
				foreach($salesdelivery as $row){
					$totaldelivery += round($row->getTotalRawPlusService(),2);
				}
				
				$totalreturn = 0;
				
				$return = ProjectSaleReturn::whereHas('projectSale', function($query) use ($idsales) {
						$query->where('sales_id',$idsales);
					})
					->whereRaw('date(created_at) BETWEEN "'.$startmonth.'-01" AND "'.date('Y-m-t', strtotime($endmonth)).'"')->get();
				
				foreach($return as $row){
					if(date('Y-m-d',strtotime($row->projectSale->created_at)) < '2022-04-01'){
						$ppnpembagi = 1.1;
					}else{
						$ppnpembagi = 1.11;
					}
					
					
					if($row->project->ppn == '1'){
						$totalreturn += $row->getTotal() / $ppnpembagi;
					}else{
						$totalreturn += $row->getTotal();
					}
				}
				
				$salesdata = User::find($idsales);
				
				$data[] = [
					'name' 	=> $salesdata->name,
					'steps'	=> round($totaldelivery - $totalreturn),
					'href'	=> $salesdata->photo()
				];
				
				$name  = array_column($data, 'name');
				$steps = array_column($data, 'steps');
				$href = array_column($data, 'href');
				
				array_multisort($steps, SORT_ASC, $data);
 			}
			
			$title = 'Sales Ranking';
		}
		
		return response()->json([
			'status'	=> 200,
			'title'		=> $title.' Period '.date('F Y',strtotime($startmonth)).' - '.date('F Y',strtotime($endmonth)),
			'data'		=> $data
		]);
	}
}