<?php

namespace App\Http\Controllers\Admin;

use App\Models\Project;
use App\Models\ProjectWarehouse;
use App\Models\ProjectNote;
use App\Models\CashBank;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;


class ReportProjectSummaryController extends Controller {
    
    public function index() 
    {
		$data = [
			'title'   => 'Project Summary',
			'content' => 'admin.report.project.project_summary'
		];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function report(Request $request)
	{
		
		if($request->method){
			
			$data = [];
			
			if($request->method == '1'){
				$result = Project::whereHas('projectSale', function($query) use ($request) {
						$query->whereHas('sales', function($query) use ($request){
							if($request->branch){
								$query->where('branch',$request->branch);
							}
						});
					})->get();
				
				foreach($result as $project){
					$totso = 0;
					$totpay = 0;
					$totreturn = 0;
					$totalcb = 0;
					
					foreach($project->projectSale as $sale){
						$totso += str_replace(',','.',str_replace('.','',$sale->getTotalRawPlusService()));
					}
					
					foreach($project->projectPay as $pay){
						$totpay += $pay->nominal;
					}
					
					foreach($project->projectSaleReturn as $return){
						$totreturn += $return->getTotal();
					}
					
					$cb = CashBank::where('lookable_type','projects')->where('lookable_id',$project->id)->get();
			
					if(count($cb) > 0){
						foreach($cb as $cbcb){
							$totalcb += $cbcb->cashBankDetail()->first()->nominal;
						}
					}
					
					if(($totso-$totpay-$totreturn-$totalcb) > 0){
						$data[] = $project;
					}
				}
			}elseif($request->method == '2'){
				$result = Project::whereHas('projectSale', function($query) use ($request) {
						$query->whereHas('sales', function($query) use ($request){
							if($request->branch){
								$query->where('branch',$request->branch);
							}
						});
					})->get();
				
				foreach($result as $project){
					$qtyso = 0;
					$qtydo = 0;
					
					foreach($project->projectSale as $sale){
						foreach($sale->projectSaleProduct as $product){
							if($product->unit == '2' || $product->unit == '3'){
								$m2 = (( $product->product->type->length * $product->product->type->width ) / 10000) * $product->product->carton_pcs;
								if($m2 < 1.1){
									$countbox = $product->qty;
								}else{
									$countbox = ceil(round($product->qty / $m2,2));
								}
							}elseif($product->unit == '1' || $product->unit == '4'){
								$countbox = $product->qty;
							}
							$qtyso += $countbox;
						}
					}
					
					foreach($project->projectDelivery as $delivery){
						foreach($delivery->projectDeliveryProduct as $product){
							$qtydo += $product->qty;
						}
					}
					
					foreach($project->projectSaleReturn as $return){
						foreach($return->projectSaleReturnProduct as $product){
							$qtydo -= $product->qty;
						}
					}
					
					if($qtyso !== $qtydo){
						$data[] = $project;
					}
				}
			}elseif($request->method == '3'){
				$result = Project::where('code','like',"%$request->project%")
				->orWhereHas('customer', function($query) use ($request) {
					$query->where('name','like',"%$request->project%");
				})
				->get();
				
				foreach($result as $project){
					$totso = 0;
					$totpay = 0;
					$totreturn = 0;
					$totalcb = 0;
					
					foreach($project->projectSale as $sale){
						$totso += str_replace(',','.',str_replace('.','',$sale->getTotalRawPlusService()));
					}
					
					foreach($project->projectPay as $pay){
						$totpay += $pay->nominal;
					}
					
					foreach($project->projectSaleReturn as $return){
						$totreturn += $return->getTotal();
					}
					
					$cb = CashBank::where('lookable_type','projects')->where('lookable_id',$project->id)->get();
			
					if(count($cb) > 0){
						foreach($cb as $cbcb){
							$totalcb += $cbcb->cashBankDetail()->first()->nominal;
						}
					}
					
					//if(($totso-$totpay-$totreturn-$totalcb) > 0){
					$data[] = $project;
					//}
				}
			}
		}else{
			$data = Project::whereRaw("timeline >= '$request->startDate' AND timeline <= '$request->endDate'")
				->whereHas('projectSale', function($query) use ($request) {
					$query->whereHas('sales', function($query) use ($request){
						if($request->branch){
							$query->where('branch',$request->branch);
						}
					});
				})->get();
		}
		
		$html = '';
		
		$no = 1;
		
		$gtWarehouseReceive = 0;
		
		foreach($data as $project){
			$html .= '
				<tr>
			';
			
			$html .= '
				<td class="text-center">
					'.$no.'.
				</td>
				<td class="text-center">
					'.$project->name.' Cust. '.$project->customer->name.'
				</td>
				<td class="text-center">
					'.$project->code.'
				</td>
			';
			
			$totalso = 0;
			$totalsofull = 0;
			$totalqtyso = 0;
			
			if(count($project->projectSale) > 0){
				$html .= '<td class="text-center">';
				
				foreach($project->projectSale as $sale){
					$html .= $sale->sales->name.'<br>';
				}
				
				$html .= '</td>';
				
				$html .= '<td class="text-center">';
				
				foreach($project->projectSale as $sale){
					$html .= $sale->code.'<br>';
				}
				
				$html .= '</td>';
				
				$html .= '<td class="text-center">';
				
				foreach($project->projectSale as $sale){
					
					foreach($sale->projectSaleProduct as $product){
						if($product->unit == '2' || $product->unit == '3'){
							$m2 = (( $product->product->type->length * $product->product->type->width ) / 10000) * $product->product->carton_pcs;
							if($m2 < 1.1 && $product->product->type->category->parent()->id !== 18){
								$countbox = ceil($product->qty);
							}else{
								if($m2 < 1.1 && date('Y-m',strtotime($project->created_at)) < '2022-06' && $product->product->type->category->parent()->id == 18){
									$countbox = ceil($product->qty);
								}else{
									$countbox = ceil(round($product->qty / $m2,2));
								}
							}
						}elseif($product->unit == '1' || $product->unit == '4'){
							$countbox = $product->qty;
						}
						$totalqtyso += $countbox;
					}
				}
				
				$html .= $totalqtyso;
				
				$html .= '</td>';
				
				$html .= '<td class="text-right">';				
				
				foreach($project->projectSale as $sale){
					$totalso += str_replace(',','.',str_replace('.','',$sale->getTotalRawPlusService()));
					$totalsofull += str_replace(',','.',str_replace('.','',$sale->getTotal()));
					$html .= $sale->getTotalRawPlusService().'<br>';
				}
				
				$html .= '</td>';
				
				$html .= '<td class="text-right">';				
				
				foreach($project->projectSale as $sale){
					$html .= $sale->getTotal().'<br>';
				}
				
				$html .= '</td>';
				
			}else{
				$html .= '
					<td class="text-center">
						<span class="badge badge-danger">None</span>
					</td>
					<td class="text-center">
						<span class="badge badge-danger">None</span>
					</td>
					<td class="text-center">
						<span class="badge badge-danger">None</span>
					</td>
					<td class="text-right">
						<span class="badge badge-danger">None</span>
					</td>
				';
			}
			
			if(count($project->projectPurchase) > 0){
				$html .= '<td class="text-center">';
				
				foreach($project->projectPurchase as $purchase){
					$html .= $purchase->code.'<br>';
				}
				
				$html .= '</td>';
				
				$html .= '<td class="text-center">';
				
				$totalqtypo = 0;
				$totalqtyrec = 0;
				
				foreach($project->projectPurchase as $purchase){
					foreach($purchase->projectPurchaseProduct as $product){
						$totalqtypo += $product->qty;
					}
					
					foreach($purchase->projectWarehouse as $warehouse){
						foreach($warehouse->projectWarehouseProduct as $product){
							$totalqtyrec += $product->qty;
						}
					}
				}
				
				$html .= $totalqtypo;
				
				$html .= '</td>';
				
				$html .= '<td class="text-center">';
				
				$html .= $totalqtyrec;
				
				$html .= '</td>';
				
				$html .= '<td class="text-right">';
				
				foreach($project->projectPurchase as $purchase){
					foreach($purchase->projectWarehouse as $warehouse){
						$gtWarehouseReceive += $warehouse->getTotalInventory();
						$html .= $warehouse->code.'-'.number_format($warehouse->getTotalInventory(),0,',','.').'<br>';
					}
				}
				
				$html .= '</td>';
				
				$html .= '<td class="text-right">';
				
				foreach($project->projectPurchase as $purchase){
					foreach($purchase->projectWarehouse as $warehouse){
						$html .= $warehouse->code.'-'.number_format($warehouse->getTotal()['totalpurchase'],0,',','.').'<br>';
					}
				}
				
				$html .= '</td>';
				
				$html .= '<td class="text-center">';
				
				$totalqtyreturnpo = 0;
				
				foreach($project->projectPurchaseReturn as $return){
					foreach($return->projectPurchaseReturnProduct as $product){
						$totalqtyreturnpo += $product->qty;
					}
				}
				
				$html .= $totalqtyreturnpo;
				
				$html .= '</td>';
				
				$html .= '<td class="text-right">';
				
				foreach($project->projectPurchase as $purchase){
					foreach(ProjectNote::where('notable_type','project_purchases')->where('notable_id',$purchase->id)->where('is_public','1')->get() as $rowtrack){
						$html .= date('d M Y',strtotime($rowtrack->created_at)).' <b>'.$rowtrack->note.'</b> with attachment '.($rowtrack->image ? '<a class="btn btn-success btn-sm" href="'.$rowtrack->image().'" target="_blank"><i class="icon-eye"></i></a>' : '<span class="badge badge-danger">None</span>' ).'<br>';
					}
					
					foreach(ProjectNote::where('notable_type','pre_purchase')->where('notable_id',$purchase->project->id)->where('is_public','1')->get() as $rowtrack){
						$html .= date('d M Y',strtotime($rowtrack->created_at)).' <b>'.$rowtrack->note.'</b> with attachment '.($rowtrack->image ? '<a class="btn btn-success btn-sm" href="'.$rowtrack->image().'" target="_blank"><i class="icon-eye"></i></a>' : '<span class="badge badge-danger">None</span>' ).'<br>';
					}
				}
				
				$html .= '</td>';
				
				$html .= '<td class="text-right">';
				
				$totalreturnpo = 0;
				
				foreach($project->projectPurchaseReturn as $return){
					$html .= '
						'.$return->code.' '.number_format($return->getTotal(),2,',','.').'<br>
					';
					$totalreturnpo += round($return->getTotal());
				}
				
				//$html .= number_format($totalreturnpo,0,',','.');
				
				$html .= '</td>';
				
			}else{
				$html .= '
					<td class="text-center">
						<span class="badge badge-danger">None</span>
					</td>
					<td class="text-center">
						<span class="badge badge-danger">None</span>
					</td>
					<td class="text-right">
						<span class="badge badge-danger">None</span>
					</td>
					<td class="text-center">
						<span class="badge badge-danger">None</span>
					</td>
					<td class="text-center">
						<span class="badge badge-danger">None</span>
					</td>
					<td class="text-center">
						<span class="badge badge-danger">None</span>
					</td>
					<td class="text-center">
						<span class="badge badge-danger">None</span>
					</td>
					<td class="text-center">
						<span class="badge badge-danger">None</span>
					</td>
				';
			}
			
			$totaldo = 0;
			
			$totalqtyreturdo = 0;
			
			$totalreturndo = 0;
			
			$totalqtydelivery = 0;
			
			$totalqtyreceived = 0;
			
			if(count($project->projectDelivery->where('is_sales','1')) > 0){
				$html .= '<td class="text-center">';
				
				foreach($project->projectDelivery->where('is_sales','1') as $delivery){
					$html .= $delivery->code.' - Cogs : '.number_format($delivery->getTotal()['totalpurchase'],0,',','.').'<br>';
				}
				
				$html .= '</td>';
				
				$html .= '<td class="text-center">';
				
				foreach($project->projectDelivery->where('is_sales','1') as $delivery){
					foreach($delivery->projectDeliveryProduct as $product){
						$totalqtydelivery += $product->qty;
					}
				}
				
				$html .= $totalqtydelivery;
				
				$html .= '</td>';
				
				$html .= '<td class="text-center">';
				
				foreach($project->projectDelivery->where('is_sales','1')->whereNotNull('received_date') as $delivery){
					foreach($delivery->projectDeliveryProduct as $product){
						$totalqtyreceived += $product->qty;
					}
				}
				
				$html .= $totalqtyreceived;
				
				$html .= '</td>';
				
				$html .= '<td class="text-right">';
				
				foreach($project->projectDelivery->where('is_sales','1')->whereNotNull('received_date') as $delivery){
					$totaldo += round($delivery->grandtotal_product + $delivery->grandtotal_service);
					$html .= date("d-M-Y",strtotime($delivery->received_date)).'-'.number_format($delivery->grandtotal_product + $delivery->grandtotal_service,0,',','.').'<br>';
				}
				
				$html .= '</td>';
				
				$html .= '<td class="text-center">';
				
				foreach($project->projectSaleReturn as $return){
					foreach($return->projectSaleReturnProduct as $product){
						$totalqtyreturdo += $product->qty;
					}
				}
				
				$html .= $totalqtyreturdo;
				
				$html .= '</td>';
				
				$html .= '<td class="text-right">';
				
				foreach($project->projectSaleReturn as $return){
					$totalreturndo += $return->getTotalNew();
					$html .= $return->code.' - '.number_format($totalreturndo,0,',','.').'<br>';
				}
				
				$html .= '</td>';
				
				$html .= '<td class="text-right">';
				
				foreach(ProjectNote::where('notable_type','pre_delivery')->where('notable_id',$project->id)->where('is_public','1')->get() as $rowpre){
					$html .= $rowpre->created_at.' '.$rowpre->note.'<br><br>';
				}
				
				foreach($project->projectDelivery->where('is_sales','1') as $delivery){
					foreach($delivery->projectDeliveryTrack as $tr){
						$html .= $tr->created_at.' '.$tr->note.'<br><br>';
					}
				}
				
				$html .= '</td>';
				
				$html .= '<td class="text-right">'.number_format($project->cutting_cost + $project->delivery_cost + $project->misc_cost,0,',','.').'</td>';
				
			}else{
				$pre_delivery = '';
				
				foreach(ProjectNote::where('notable_type','pre_delivery')->where('notable_id',$project->id)->where('is_public','1')->get() as $rowpre){
					$pre_delivery .= $rowpre->created_at.' '.$rowpre->note.'<br><br>';
				}
				
				$html .= '
					<td class="text-center">
						<span class="badge badge-danger">None</span>
					</td>
					<td class="text-center">
						<span class="badge badge-danger">None</span>
					</td>
					<td class="text-right">
						<span class="badge badge-danger">None</span>
					</td>
					<td class="text-center">
						<span class="badge badge-danger">None</span>
					</td>
					<td class="text-right">
						<span class="badge badge-danger">None</span>
					</td>
					<td class="text-right">
						<span class="badge badge-danger">None</span>
					</td>
					<td class="text-right">
						<span class="badge badge-danger">'.$pre_delivery.'</span>
					</td>
				';
			}
			
			$totalpay = 0;
			$totalcb = 0;
			$totalcost = $project->cutting_cost + $project->delivery_cost + $project->misc_cost;
			
			if(count($project->projectPay) > 0){
				$html .= '<td class="text-center">';
				
				foreach($project->projectPay as $pay){
					$html .= $pay->code.'-'.date("d M Y",strtotime($pay->date)).'<br>';
				}
				
				$html .= '</td>';
				
				$html .= '<td class="text-right">';
				
				foreach($project->projectPay as $pay){
					$totalpay += $pay->nominal;
					$html .= $pay->coa->name.' - '.number_format($pay->nominal,0,',','.').'<br>';
				}
				
				$cb = CashBank::where('lookable_type','projects')->where('lookable_id',$project->id)->where('code','NOT LIKE',"FEE-PTA%")->where('code','not like',"FEE-SMB%")->get();
				
				if(count($cb) > 0){
					foreach($cb as $rowcb){
						foreach($rowcb->cashBankDetail()->where('coa_id',27)->get() as $cbcb){
							if($cbcb->type == '1'){
								$totalcb -= $cbcb->nominal;
								$html .= '-'.number_format($cbcb->nominal,0,',','.').'<br>';
							}else{
								$totalcb += $cbcb->nominal;
								$html .= '+'.number_format($cbcb->nominal,0,',','.').'<br>';
							}
						}
					}
				}
				
				$html .= '</td>';
				
			}else{
				$html .= '
					<td class="text-center">
						<span class="badge badge-danger">None</span>
					</td>
					<td class="text-right">
						<span class="badge badge-danger">None</span>
					</td>
				';
			}
			
			$totalqtybalance = $totalqtyso - $totalqtydelivery + $totalqtyreturdo;
			$totalbalance = $totaldo + $totalcost - $totalreturndo - $totalpay - $totalcb;
			$totalpayment = $totaldo + $totalcost - $totalreturndo - $totalpay - $totalcb;
			
				$html .= '
					<td class="text-center">
						'.$totalqtybalance.'
					</td>
					<td class="text-right">
						'.number_format($totalbalance,0,',','.').'
					</td>
					<td class="text-right">
						'.number_format($totalpayment,0,',','.').'
					</td>
				';
			
			$html .= '
				</tr>
			';
			
			$no++;
		}
		
		$html .= '
			<tr>
				<td colspan="11" class="text-center bg-warning"></td>
				<td class="text-center bg-warning">'.number_format($gtWarehouseReceive,0,',','.').'</td>
				<td colspan="17" class="text-center bg-warning"></td>
			</tr>
		';
		
		return response()->json([
			'status'	=> 200,
			'content'	=> $html
		]);
	}
}