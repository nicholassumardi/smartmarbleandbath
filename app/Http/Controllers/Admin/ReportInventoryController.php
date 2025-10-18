<?php

namespace App\Http\Controllers\Admin;

use App\Exports\InventoryExport;
use App\Helper\SMB;
use Illuminate\Support\Facades\DB;
use App\Models\ProjectPurchase;
use App\Models\TransferProduct;
use App\Models\Transfer;
use App\Models\Stock;
use App\Models\ProductCogs;
use App\Models\ProjectWarehouse;
use App\Models\ProjectWarehouseProduct;
use App\Models\ProjectDelivery;
use App\Models\ProjectDeliveryProduct;
use App\Models\ProjectSaleReturnProduct;
use App\Models\ProjectPurchaseReturnProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Type;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;

use function PHPUnit\Framework\isNull;

class ReportInventoryController extends Controller
{

	public function index(Request $request)
	{

		// $startDate = new Carbon('2022-01-01'); 
		// $date = $startDate->toDateString();
		// $dataOut = ProjectDeliveryProduct::whereHas('projectDelivery',function($query) use($date){
		// 	$query->whereHas('projectSale',function($query) use ($date){
		// 		$query->whereHas('sales',function($query) use($date){
		// 			$query->where('branch',2);
		// 		});
		// 	})->whereRaw("DATE(received_date) = '$date'");
		// })->first();

		// dd($dataOut->id);
		$uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
		$uri = explode('/', $uri_path);

		if ($uri[4] == 'product_inventory') {
			// $explode = explode(';',$request->size); 
			// array[0] length, array[1] width
			// dd($explode[0]);
			$filter = $request->filter ? $request->filter : date('Y-m');
			$size = Type::where('length', '!=', NULL)->where('width', '!=', NULL)->groupBy('length')->groupBy('width')->get();
			$warehouse = Warehouse::all();
			$data   = [
				'title'         	=> 'Product Inventory',
				'filter'			=> $filter,
				'brands'			=> Brand::all(),
				'sizes'				=> $size,
				'warehouses'		=> $warehouse,
				'content'       	=> 'admin.report.inventory.product_inventory'
			];
		} else {
			$data = [
				'title'   		=> 'Stock Card',
				'content' 		=> 'admin.report.inventory.stock_card'
			];
		}

		return view('admin.layouts.index', ['data' => $data]);
	}

	public function getStock(Request $request)
	{
		$branch = $request->branch;

		$rowPrevious = ProductCogs::where(function ($query) use ($request) {
			$query->whereHas('product', function ($query) use ($request) {
				if ($request->brand) {
					$query->whereHas('brand', function ($query) use ($request) {
						$query->where('name', 'LIKE', "%$request->brand%");
					});
				}

				if ($request->size) {
					$size = explode(';', $request->size); // array[0] length, array[1] width
					$query->whereHas('type', function ($query) use ($size) {
						$query->where('length', $size[0])
							->where('width', $size[1]);
					});
				}
			});
		})->where('branch', $branch)->where('date', '<', $request->startDate)->whereRaw("id IN (SELECT max(id) FROM product_cogs WHERE branch = '$branch' AND date < '$request->startDate' GROUP BY product_id)")->get();

		$arrResult = [];

		foreach ($rowPrevious as $row) {

			$ada = false;

			if (count($arrResult) > 0) {
				foreach ($arrResult as $key => $cek) {
					if ($cek['product_id'] == $row->product_id) {
						$ada = true;
						$index = $key;
					}
				}
			}

			if ($ada == true) {
				$arrResult[$index]['qty_before'] = $arrResult[$index]['qty_before'] + $row->qty_final;
				$arrResult[$index]['total_before'] = $arrResult[$index]['total_before'] + ($row->qty_final * $row->price_final);
			} else {
				$arrResult[] = [
					'product_id' 	=> $row->product_id,
					'product_name'	=> $row->product->name(),
					'image'		    => $row->product->type->image(),
					'brand_name'	=> $row->product->brand->name,
					'm2'			=> $row->product->m2Size(),
					'thickness'		=> $row->product->type->thickness,
					'carton_pcs'	=> $row->product->carton_pcs,
					'size'			=> $row->product->type->length . ' cm' . ' x ' . $row->product->type->width . ' cm',
					'qty_before'	=> $row->qty_final,
					'total_before'	=> $row->qty_final * $row->price_final,
					'qty'			=> 0,
					'total'			=> 0,
					'qty_adjust'	=> 0,
					'total_adjust'	=> 0,
					'qty_used'		=> 0,
					'total_used'	=> 0,
				];
			}
		}

		$rowToday = ProductCogs::where(function ($query) use ($request) {
			$query->whereHas('product', function ($query) use ($request) {
				if ($request->brand) {
					$query->whereHas('brand', function ($query) use ($request) {
						$query->where('name', 'LIKE', "%$request->brand%");
					});
				}

				if ($request->size) {
					$size = explode(';', $request->size); // array[0] length, array[1] width
					$query->whereHas('type', function ($query) use ($size) {
						$query->where('length', $size[0])
							->where('width', $size[1]);
					});
				}
			});
		})->where('branch', $branch)->where('date', '>=', $request->startDate)->where('date', '<=', $request->endDate)->get();

		foreach ($rowToday->whereIn('type', ['WE-IN', 'WR']) as $row) {

			$adakuy = false;

			if (count($arrResult) > 0) {
				foreach ($arrResult as $key => $cek) {
					if ($cek['product_id'] == $row->product_id) {
						$adakuy = true;
						$index = $key;
					}
				}
			}

			if ($adakuy == true) {
				$arrResult[$index]['qty'] = $arrResult[$index]['qty'] + $row->qty_in;
				$arrResult[$index]['total'] = $arrResult[$index]['total'] + $row->total_in;
			} else {
				$arrResult[] = [
					'product_id' 	=> $row->product_id,
					'product_name'	=> $row->product->name(),
					'image'		    => $row->product->type->image(),
					'brand_name'	=> $row->product->brand->name,
					'm2'			=> $row->product->m2Size(),
					'thickness'		=> $row->product->type->thickness,
					'carton_pcs'	=> $row->product->carton_pcs,
					'size'			=> $row->product->type->length . ' cm' . ' x ' . $row->product->type->width . ' cm',
					'qty_before'	=> 0,
					'total_before'	=> 0,
					'qty'			=> 0 + $row->qty_in,
					'total'			=> 0 + $row->total_in,
					'qty_adjust'	=> 0,
					'total_adjust'	=> 0,
					'qty_used'		=> 0,
					'total_used'	=> 0,
				];
			}
		}

		foreach ($rowToday->whereIn('type', ['DO', 'SR']) as $row) {

			$adakuy = false;

			if (count($arrResult) > 0) {
				foreach ($arrResult as $key => $cek) {
					if ($cek['product_id'] == $row->product_id) {
						$adakuy = true;
						$index = $key;
					}
				}
			}

			if ($adakuy == true) {
				$arrResult[$index]['qty_used'] = $row->type == 'DO' ? ($arrResult[$index]['qty_used'] + $row->qty_out) : ($arrResult[$index]['qty_used'] - $row->qty_in);
				$arrResult[$index]['total_used'] = $row->type == 'DO' ? ($arrResult[$index]['total_used'] + $row->total_out) : ($arrResult[$index]['total_used'] - $row->total_in);
			} else {
				$arrResult[] = [
					'product_id' 	=> $row->product_id,
					'product_name'	=> $row->product->name(),
					'image'		    => $row->product->type->image(),
					'brand_name'	=> $row->product->brand->name,
					'm2'			=> $row->product->m2Size(),
					'thickness'		=> $row->product->type->thickness,
					'carton_pcs'	=> $row->product->carton_pcs,
					'size'			=> $row->product->type->length . ' cm' . ' x ' . $row->product->type->width . ' cm',
					'qty_before'	=> 0,
					'total_before'	=> 0,
					'qty'			=> 0,
					'total'			=> 0,
					'qty_adjust'	=> 0,
					'total_adjust'	=> 0,
					'qty_used'		=> $row->type == 'DO' ? (0 + $row->qty_out) : (0 - $row->qty_in),
					'total_used'	=> $row->type == 'DO' ? (0 + $row->total_out) : (0 - $row->total_in),
				];
			}
		}

		foreach ($rowToday->whereIn('type', ['WE-OUT', 'PR']) as $row) {

			$adakuy = false;

			if (count($arrResult) > 0) {
				foreach ($arrResult as $key => $cek) {
					if ($cek['product_id'] == $row->product_id) {
						$adakuy = true;
						$index = $key;
					}
				}
			}

			if ($adakuy == true) {
				$arrResult[$index]['qty_adjust'] = $arrResult[$index]['qty_adjust'] + $row->qty_out;
				$arrResult[$index]['total_adjust'] = $arrResult[$index]['total_adjust'] + $row->total_out;
			} else {
				$arrResult[] = [
					'product_id' 	=> $row->product_id,
					'product_name'	=> $row->product->name(),
					'image'		    => $row->product->type->image(),
					'brand_name'	=> $row->product->brand->name,
					'm2'			=> $row->product->m2Size(),
					'thickness'		=> $row->product->type->thickness,
					'carton_pcs'	=> $row->product->carton_pcs,
					'size'			=> $row->product->type->length . ' cm' . ' x ' . $row->product->type->width . ' cm',
					'qty_before'	=> 0,
					'total_before'	=> 0,
					'qty'			=> 0,
					'total'			=> 0,
					'qty_adjust'	=> 0 + $row->qty_out,
					'total_adjust'	=> 0 + $row->total_out,
					'qty_used'		=> 0,
					'total_used'	=> 0,
				];
			}
		}

		$array = collect($arrResult)->sortBy('size')->toArray();

		return $array;
	}

	public function report(Request $request)
	{

		$html = '';

		$branch = $request->branch;

		$rowPrevious = ProductCogs::where('branch', $branch)->where('date', '<', $request->startDate)->whereRaw("id IN (SELECT max(id) FROM product_cogs WHERE branch = '$branch' AND date < '$request->startDate' GROUP BY product_id)")->get();

		$arrResult = [];

		foreach ($rowPrevious as $row) {

			$ada = false;

			if (count($arrResult) > 0) {
				foreach ($arrResult as $key => $cek) {
					if ($cek['product_id'] == $row->product_id) {
						$ada = true;
						$index = $key;
					}
				}
			}

			if ($ada == true) {
				$arrResult[$index]['qty_before'] = $arrResult[$index]['qty_before'] + $row->qty_final;
				$arrResult[$index]['total_before'] = $arrResult[$index]['total_before'] + ($row->qty_final * $row->price_final);
			} else {
				$arrResult[] = [
					'product_id' 	=> $row->product_id,
					'product_name'	=> $row->product->name(),
					'qty_before'	=> $row->qty_final,
					'total_before'	=> $row->qty_final * $row->price_final,
					'qty'			=> 0,
					'total'			=> 0,
					'qty_adjust'	=> 0,
					'total_adjust'	=> 0,
					'qty_used'		=> 0,
					'total_used'	=> 0,
				];
			}
		}

		$rowToday = ProductCogs::where('branch', $branch)->where('date', '>=', $request->startDate)->where('date', '<=', $request->endDate)->get();

		foreach ($rowToday->whereIn('type', ['WE-IN', 'WR']) as $row) {

			$adakuy = false;

			if (count($arrResult) > 0) {
				foreach ($arrResult as $key => $cek) {
					if ($cek['product_id'] == $row->product_id) {
						$adakuy = true;
						$index = $key;
					}
				}
			}

			if ($adakuy == true) {
				$arrResult[$index]['qty'] = $arrResult[$index]['qty'] + $row->qty_in;
				$arrResult[$index]['total'] = $arrResult[$index]['total'] + $row->total_in;
			} else {
				$arrResult[] = [
					'product_id' 	=> $row->product_id,
					'product_name'	=> $row->product->name(),
					'qty_before'	=> 0,
					'total_before'	=> 0,
					'qty'			=> 0 + $row->qty_in,
					'total'			=> 0 + $row->total_in,
					'qty_adjust'	=> 0,
					'total_adjust'	=> 0,
					'qty_used'		=> 0,
					'total_used'	=> 0,
				];
			}
		}

		foreach ($rowToday->whereIn('type', ['DO', 'SR']) as $row) {

			$adakuy = false;

			if (count($arrResult) > 0) {
				foreach ($arrResult as $key => $cek) {
					if ($cek['product_id'] == $row->product_id) {
						$adakuy = true;
						$index = $key;
					}
				}
			}

			if ($adakuy == true) {
				$arrResult[$index]['qty_used'] = $row->type == 'DO' ? ($arrResult[$index]['qty_used'] + $row->qty_out) : ($arrResult[$index]['qty_used'] - $row->qty_in);
				$arrResult[$index]['total_used'] = $row->type == 'DO' ? ($arrResult[$index]['total_used'] + $row->total_out) : ($arrResult[$index]['total_used'] - $row->total_in);
			} else {
				$arrResult[] = [
					'product_id' 	=> $row->product_id,
					'product_name'	=> $row->product->name(),
					'qty_before'	=> 0,
					'total_before'	=> 0,
					'qty'			=> 0,
					'total'			=> 0,
					'qty_adjust'	=> 0,
					'total_adjust'	=> 0,
					'qty_used'		=> $row->type == 'DO' ? (0 + $row->qty_out) : (0 - $row->qty_in),
					'total_used'	=> $row->type == 'DO' ? (0 + $row->total_out) : (0 - $row->total_in),
				];
			}
		}

		foreach ($rowToday->whereIn('type', ['WE-OUT', 'PR']) as $row) {

			$adakuy = false;

			if (count($arrResult) > 0) {
				foreach ($arrResult as $key => $cek) {
					if ($cek['product_id'] == $row->product_id) {
						$adakuy = true;
						$index = $key;
					}
				}
			}

			if ($adakuy == true) {
				$arrResult[$index]['qty_adjust'] = $arrResult[$index]['qty_adjust'] + $row->qty_out;
				$arrResult[$index]['total_adjust'] = $arrResult[$index]['total_adjust'] + $row->total_out;
			} else {
				$arrResult[] = [
					'product_id' 	=> $row->product_id,
					'product_name'	=> $row->product->name(),
					'qty_before'	=> 0,
					'total_before'	=> 0,
					'qty'			=> 0,
					'total'			=> 0,
					'qty_adjust'	=> 0 + $row->qty_out,
					'total_adjust'	=> 0 + $row->total_out,
					'qty_used'		=> 0,
					'total_used'	=> 0,
				];
			}
		}

		$array = collect($arrResult)->sortBy('product_name')->toArray();

		$no = 1;

		$grandtotalbefore = 0;
		$grandtotalreceive = 0;
		$grandtotaltotal = 0;
		$grandtotalused = 0;
		$grandtotaladjust = 0;
		$grandtotalfinal = 0;

		foreach ($array as $row) {

			$totalqty = $row['qty_before'] + $row['qty'];
			$pricebefore = $row['qty_before'] ? $row['total_before'] / $row['qty_before'] : 0;
			$pricenow = $row['qty'] ? $row['total'] / $row['qty'] : 0;
			$pricetotal = $totalqty ? ($row['total'] + $row['total_before']) / $totalqty : 0;
			$priceused = $row['qty_used'] ? $row['total_used'] / $row['qty_used'] : 0;
			$priceadjust = $row['qty_adjust'] ? $row['total_adjust'] / $row['qty_adjust'] : 0;
			$totalqtyfinal = $totalqty - $row['qty_used'] - $row['qty_adjust'];
			$pricefinal = $totalqtyfinal ? ($row['total'] + $row['total_before'] - $row['total_used'] - $row['total_adjust']) / $totalqtyfinal : 0;

			$finalqty = $totalqty - $row['qty_used'] - $row['qty_adjust'];

			if ($request->mode == '1') {
				$html .= '
					<tr>
						<td>' . $row['product_name'] . ' - ' . $row['product_id'] . '</td>
						<td class="text-right">' . $row['qty_before'] . '</td>
						<td class="text-right">Rp' . number_format($pricebefore, 2, ',', '.') . '</td>
						<td class="text-right">Rp' . number_format(round($row['total_before']), 0, ',', '.') . '</td>
						<td class="text-right">' . $row['qty'] . '</td>
						<td class="text-right">Rp' . number_format($pricenow, 2, ',', '.') . '</td>
						<td class="text-right">Rp' . number_format(round($row['total']), 0, ',', '.') . '</td>
						<td class="text-right">' . $totalqty . '</td>
						<td class="text-right">Rp' . number_format($pricetotal, 2, ',', '.') . '</td>
						<td class="text-right">Rp' . number_format(round($row['total'] + $row['total_before']), 0, ',', '.') . '</td>
						<td class="text-right">' . ($row['qty_used']) . '</td>
						<td class="text-right">Rp' . (number_format($priceused, 2, ',', '.')) . '</td>
						<td class="text-right">Rp' . (number_format(round($row['total_used']), 0, ',', '.')) . '</td>
						<td class="text-right">' . ($row['qty_adjust']) . '</td>
						<td class="text-right">Rp' . (number_format($priceadjust, 2, ',', '.')) . '</td>
						<td class="text-right">Rp' . (number_format(round($row['total_adjust']), 0, ',', '.')) . '</td>
						<td class="text-right">' . ($totalqty - $row['qty_used'] - $row['qty_adjust']) . '</td>
						<td class="text-right">Rp' . number_format($pricefinal, 2, ',', '.') . '</td>
						<td class="text-right">Rp' . ($finalqty == 0 ? 0 : number_format(round($row['total'] + $row['total_before'] - $row['total_used'] - $row['total_adjust']), 0, ',', '.')) . '</td>
					</tr>
				';

				/* $datastok = Stock::where('product_id',$row['product_id'])->where('branch',$branch)->get();
				
				foreach($datastok as $rowstok){
					$rowstok->update([
						'price_per_unit'	=> round($pricefinal,2)
					]);
				} */

				$grandtotalbefore += round($row['total_before']);
				$grandtotalreceive += round($row['total']);
				$grandtotaltotal += round($row['total'] + $row['total_before']);
				$grandtotalused += round($row['total_used']);
				$grandtotaladjust += round($row['total_adjust']);
				$grandtotalfinal += round($row['total'] + $row['total_before'] - $row['total_used'] - $row['total_adjust']);
			} elseif ($request->mode == '2') {
				if ($totalqtyfinal > 0) {
					$html .= '
					<tr>
						<td>' . $row['product_name'] . ' - ' . $row['product_id'] . '</td>
						<td class="text-right">' . $row['qty_before'] . '</td>
						<td class="text-right">Rp' . number_format($pricebefore, 2, ',', '.') . '</td>
						<td class="text-right">Rp' . number_format(round($row['total_before']), 0, ',', '.') . '</td>
						<td class="text-right">' . $row['qty'] . '</td>
						<td class="text-right">Rp' . number_format($pricenow, 2, ',', '.') . '</td>
						<td class="text-right">Rp' . number_format(round($row['total']), 0, ',', '.') . '</td>
						<td class="text-right">' . $totalqty . '</td>
						<td class="text-right">Rp' . number_format($pricetotal, 2, ',', '.') . '</td>
						<td class="text-right">Rp' . number_format(round($row['total'] + $row['total_before']), 0, ',', '.') . '</td>
						<td class="text-right">' . ($row['qty_used']) . '</td>
						<td class="text-right">Rp' . (number_format($priceused, 2, ',', '.')) . '</td>
						<td class="text-right">Rp' . (number_format(round($row['total_used']), 0, ',', '.')) . '</td>
						<td class="text-right">' . ($row['qty_adjust']) . '</td>
						<td class="text-right">Rp' . (number_format($priceadjust, 2, ',', '.')) . '</td>
						<td class="text-right">Rp' . (number_format(round($row['total_adjust']), 0, ',', '.')) . '</td>
						<td class="text-right">' . ($totalqty - $row['qty_used'] - $row['qty_adjust']) . '</td>
						<td class="text-right">Rp' . number_format($pricefinal, 2, ',', '.') . '</td>
						<td class="text-right">Rp' . ($finalqty == 0 ? 0 : number_format(round($row['total'] + $row['total_before'] - $row['total_used'] - $row['total_adjust']), 0, ',', '.')) . '</td>
					</tr>
				';


					$grandtotalbefore += round($row['total_before']);
					$grandtotalreceive += round($row['total']);
					$grandtotaltotal += round($row['total'] + $row['total_before']);
					$grandtotalused += round($row['total_used']);
					$grandtotaladjust += round($row['total_adjust']);
					$grandtotalfinal += round($row['total'] + $row['total_before'] - $row['total_used'] - $row['total_adjust']);
				}
			}

			$no++;
		}

		$html .= '
			<tr class="text-center">
				<th>Total</th>
				<th>...</th>
				<th>...</th>
				<th>Rp' . number_format($grandtotalbefore, 2, ',', '.') . '</th>
				<th>...</th>
				<th>...</th>
				<th>Rp' . number_format($grandtotalreceive, 2, ',', '.') . '</th>
				<th>...</th>
				<th>...</th>
				<th>Rp' . number_format($grandtotaltotal, 2, ',', '.') . '</th>
				<th>...</th>
				<th>...</th>
				<th>Rp' . number_format($grandtotalused, 2, ',', '.') . '</th>
				<th>...</th>
				<th>...</th>
				<th>Rp' . number_format($grandtotaladjust, 2, ',', '.') . '</th>
				<th>...</th>
				<th>...</th>
				<th>Rp' . number_format($grandtotalfinal, 2, ',', '.') . '</th>
			</tr>
		';

		return response()->json([
			'status' 	=> 200,
			'content'	=> $html
		]);
	}


	public function print(Request $request)
	{

		$branch = $request->branch;

		$rowPrevious = ProductCogs::where('branch', $branch)->where('date', '<', $request->startDate)->whereRaw("id IN (SELECT max(id) FROM product_cogs WHERE branch = '$branch' AND date < '$request->startDate' GROUP BY product_id)")->get();

		$arrResult = [];

		foreach ($rowPrevious as $row) {

			$ada = false;

			if (count($arrResult) > 0) {
				foreach ($arrResult as $key => $cek) {
					if ($cek['product_id'] == $row->product_id) {
						$ada = true;
						$index = $key;
					}
				}
			}

			if ($ada == true) {
				$arrResult[$index]['qty_before'] = $arrResult[$index]['qty_before'] + $row->qty_final;
				$arrResult[$index]['total_before'] = $arrResult[$index]['total_before'] + ($row->qty_final * $row->price_final);
			} else {
				$arrResult[] = [
					'product_id' 	=> $row->product_id,
					'product_name'	=> $row->product->name(),
					'qty_before'	=> $row->qty_final,
					'total_before'	=> $row->qty_final * $row->price_final,
					'qty'			=> 0,
					'total'			=> 0,
					'qty_adjust'	=> 0,
					'total_adjust'	=> 0,
					'qty_used'		=> 0,
					'total_used'	=> 0,
				];
			}
		}

		$rowToday = ProductCogs::where('branch', $branch)->where('date', '>=', $request->startDate)->where('date', '<=', $request->endDate)->get();

		foreach ($rowToday->whereIn('type', ['WE-IN', 'WR']) as $row) {

			$adakuy = false;

			if (count($arrResult) > 0) {
				foreach ($arrResult as $key => $cek) {
					if ($cek['product_id'] == $row->product_id) {
						$adakuy = true;
						$index = $key;
					}
				}
			}

			if ($adakuy == true) {
				$arrResult[$index]['qty'] = $arrResult[$index]['qty'] + $row->qty_in;
				$arrResult[$index]['total'] = $arrResult[$index]['total'] + $row->total_in;
			} else {
				$arrResult[] = [
					'product_id' 	=> $row->product_id,
					'product_name'	=> $row->product->name(),
					'qty_before'	=> 0,
					'total_before'	=> 0,
					'qty'			=> 0 + $row->qty_in,
					'total'			=> 0 + $row->total_in,
					'qty_adjust'	=> 0,
					'total_adjust'	=> 0,
					'qty_used'		=> 0,
					'total_used'	=> 0,
				];
			}
		}

		foreach ($rowToday->whereIn('type', ['DO', 'SR']) as $row) {

			$adakuy = false;

			if (count($arrResult) > 0) {
				foreach ($arrResult as $key => $cek) {
					if ($cek['product_id'] == $row->product_id) {
						$adakuy = true;
						$index = $key;
					}
				}
			}

			if ($adakuy == true) {
				$arrResult[$index]['qty_used'] = $row->type == 'DO' ? ($arrResult[$index]['qty_used'] + $row->qty_out) : ($arrResult[$index]['qty_used'] - $row->qty_in);
				$arrResult[$index]['total_used'] = $row->type == 'DO' ? ($arrResult[$index]['total_used'] + $row->total_out) : ($arrResult[$index]['total_used'] - $row->total_in);
			} else {
				$arrResult[] = [
					'product_id' 	=> $row->product_id,
					'product_name'	=> $row->product->name(),
					'qty_before'	=> 0,
					'total_before'	=> 0,
					'qty'			=> 0,
					'total'			=> 0,
					'qty_adjust'	=> 0,
					'total_adjust'	=> 0,
					'qty_used'		=> $row->type == 'DO' ? (0 + $row->qty_out) : (0 - $row->qty_in),
					'total_used'	=> $row->type == 'DO' ? (0 + $row->total_out) : (0 - $row->total_in),
				];
			}
		}

		foreach ($rowToday->whereIn('type', ['WE-OUT', 'PR']) as $row) {

			$adakuy = false;

			if (count($arrResult) > 0) {
				foreach ($arrResult as $key => $cek) {
					if ($cek['product_id'] == $row->product_id) {
						$adakuy = true;
						$index = $key;
					}
				}
			}

			if ($adakuy == true) {
				$arrResult[$index]['qty_adjust'] = $arrResult[$index]['qty_adjust'] + $row->qty_out;
				$arrResult[$index]['total_adjust'] = $arrResult[$index]['total_adjust'] + $row->total_out;
			} else {
				$arrResult[] = [
					'product_id' 	=> $row->product_id,
					'product_name'	=> $row->product->name(),
					'qty_before'	=> 0,
					'total_before'	=> 0,
					'qty'			=> 0,
					'total'			=> 0,
					'qty_adjust'	=> 0 + $row->qty_out,
					'total_adjust'	=> 0 + $row->total_out,
					'qty_used'		=> 0,
					'total_used'	=> 0,
				];
			}
		}

		$array = collect($arrResult)->sortBy('product_name')->toArray();

		return view('admin.pdf.report.inventory.product', [
			'array' 	=> $array,
			'branch'	=> $request->branch,
			'mode'	    => $request->mode,
			'startDate'	=> $request->startDate,
			'endDate'	=> $request->endDate
		]);
	}

	public function print_old(Request $request)
	{

		$branch = $request->branch;

		$rows = ProjectWarehouseProduct::whereHas('projectWarehouse', function ($query) use ($branch) {
			$query->whereHas('projectPurchase', function ($query) use ($branch) {
				$query->whereHas('sales', function ($query) use ($branch) {
					$query->where('branch', $branch);
				});
			})->where('include_cost', '1');
		})
			->get();

		$arrResult = [];

		foreach ($rows as $row) {

			$ada = false;

			if (count($arrResult) > 0) {
				foreach ($arrResult as $key => $cek) {
					if ($cek['product_id'] == $row->product_id) {
						$ada = true;
						$index = $key;
					}
				}
			}

			if ($ada == true) {
				if (substr($row->projectWarehouse->date_receive, 0, 10) >= $request->startDate && substr($row->projectWarehouse->date_receive, 0, 10) <= $request->endDate) {
					$arrResult[$index]['qty'] = $arrResult[$index]['qty'] + $row->qty;
					$arrResult[$index]['total'] = $arrResult[$index]['total'] + (str_replace(',', '.', str_replace('.', '', $row->purchasePrice())) * $row->qty);
				} elseif (substr($row->projectWarehouse->date_receive, 0, 10) < $request->startDate) {
					$arrResult[$index]['qty_before'] = $arrResult[$index]['qty_before'] + $row->qty;
					$arrResult[$index]['total_before'] = $arrResult[$index]['total_before'] + (str_replace(',', '.', str_replace('.', '', $row->purchasePrice())) * $row->qty);
				}
			} else {
				if (substr($row->projectWarehouse->date_receive, 0, 10) >= $request->startDate && substr($row->projectWarehouse->date_receive, 0, 10) <= $request->endDate) {
					$arrResult[] = [
						'product_id' 	=> $row->product_id,
						'product_name'	=> $row->product->name(),
						'qty_before'	=> 0,
						'total_before'	=> 0,
						'qty'			=> $row->qty,
						'total'			=> str_replace(',', '.', str_replace('.', '', $row->purchasePrice())) * $row->qty,
						'qty_used'		=> 0,
						'total_used'	=> 0,
					];
				} elseif (substr($row->projectWarehouse->date_receive, 0, 10) < $request->startDate) {
					$arrResult[] = [
						'product_id' 	=> $row->product_id,
						'product_name'	=> $row->product->name(),
						'qty_before'	=> $row->qty,
						'total_before'	=> str_replace(',', '.', str_replace('.', '', $row->purchasePrice())) * $row->qty,
						'qty'			=> 0,
						'total'			=> 0,
						'qty_used'		=> 0,
						'total_used'	=> 0,
					];
				}
			}
		}

		$rows = TransferProduct::whereHas('transfer', function ($query) use ($branch) {
			$query->where('branch', $branch)->whereNotNull('for_starting');
		})->orWhereHas('transfer', function ($query) use ($branch) {
			$query->where('branch', $branch)->whereNotNull('for_in_transfer');
		})->get();

		foreach ($rows as $row) {

			$ada = false;

			if (count($arrResult) > 0) {
				foreach ($arrResult as $key => $cek) {
					if ($cek['product_id'] == $row->product_id) {
						$ada = true;
						$index = $key;
					}
				}
			}

			if ($ada == true) {
				if ($row->transfer->date >= $request->startDate && $row->transfer->date <= $request->endDate) {
					$arrResult[$index]['qty'] = $arrResult[$index]['qty'] + $row->qty;
					$arrResult[$index]['total'] = $arrResult[$index]['total'] + $row->price;
				} elseif ($row->transfer->date < $request->startDate) {
					$arrResult[$index]['qty_before'] = $arrResult[$index]['qty_before'] + $row->qty;
					$arrResult[$index]['total_before'] = $arrResult[$index]['total_before'] + $row->price;
				}
			} else {
				if ($row->transfer->date >= $request->startDate && $row->transfer->date <= $request->endDate) {
					$arrResult[] = [
						'product_id' 	=> $row->product_id,
						'product_name'	=> $row->product->name(),
						'qty_before'	=> 0,
						'total_before'	=> 0,
						'qty'			=> $row->qty,
						'total'			=> $row->price,
						'qty_used'		=> 0,
						'total_used'	=> 0,
					];
				} elseif ($row->transfer->date < $request->startDate) {
					$arrResult[] = [
						'product_id' 	=> $row->product_id,
						'product_name'	=> $row->product->name(),
						'qty_before'	=> $row->qty,
						'total_before'	=> $row->price,
						'qty'			=> 0,
						'total'			=> 0,
						'qty_used'		=> 0,
						'total_used'	=> 0,
					];
				}
			}
		}

		$rows = ProjectDeliveryProduct::whereHas('projectDelivery', function ($query) use ($branch) {
			$query
				->whereNotNull('received_date')
				/* ->where('is_sales','1') */
				->whereHas('projectSale', function ($query) use ($branch) {
					$query->whereHas('sales', function ($query) use ($branch) {
						$query->where('branch', $branch);
					});
				});
		})
			->get();

		foreach ($rows as $row) {

			$adakuy = false;

			if (count($arrResult) > 0) {
				foreach ($arrResult as $key => $cek) {
					if ($cek['product_id'] == $row->product_id) {
						$adakuy = true;
						$index = $key;
					}
				}
			}

			if ($adakuy == true) {
				if ($row->projectDelivery->received_date >= $request->startDate && $row->projectDelivery->received_date <= $request->endDate) {
					$arrResult[$index]['qty_used'] = $arrResult[$index]['qty_used'] + $row->qty;
					$arrResult[$index]['total_used'] = $arrResult[$index]['total_used'] + (str_replace(',', '.', str_replace('.', '', $row->purchasePrice())) * $row->qty);
				} elseif ($row->projectDelivery->received_date < $request->startDate) {
					$arrResult[$index]['qty_before'] = $arrResult[$index]['qty_before'] - $row->qty;
					$arrResult[$index]['total_before'] = $arrResult[$index]['total_before'] - (str_replace(',', '.', str_replace('.', '', $row->purchasePrice())) * $row->qty);
				}
			} else {
				if ($row->projectDelivery->received_date >= $request->startDate && $row->projectDelivery->received_date <= $request->endDate) {
					$arrResult[] = [
						'product_id' 	=> $row->product_id,
						'product_name'	=> $row->product->name(),
						'qty_before'	=> 0,
						'total_before'	=> 0,
						'qty'			=> 0,
						'total'			=> 0,
						'qty_used'		=> $row->qty,
						'total_used'	=> str_replace(',', '.', str_replace('.', '', $row->purchasePrice())) * $row->qty
					];
				} elseif ($row->projectDelivery->received_date < $request->startDate) {
					$arrResult[] = [
						'product_id' 	=> $row->product_id,
						'product_name'	=> $row->product->name(),
						'qty_before'	=> 0 - $row->qty,
						'total_before'	=> 0 - (str_replace(',', '.', str_replace('.', '', $row->purchasePrice())) * $row->qty),
						'qty'			=> 0,
						'total'			=> 0,
						'qty_used'		=> 0,
						'total_used'	=> 0,
					];
				}
			}
		}

		$rows = TransferProduct::whereHas('transfer', function ($query) use ($branch) {
			$query->where('branch', $branch)->whereNotNull('for_customer')->where('status', '3');
		})->orWhereHas('transfer', function ($query) use ($branch) {
			$query->where('branch', $branch)->whereNotNull('for_customer')->where('status', '2');
		})->orWhereHas('transfer', function ($query) use ($branch) {
			$query->where('branch', $branch)->whereNotNull('for_customer')->where('status', '1');
		})->orWhereHas('transfer', function ($query) use ($branch) {
			$query->where('branch', $branch)->whereNotNull('for_correction');
		})->orWhereHas('transfer', function ($query) use ($branch) {
			$query->where('branch', $branch)->whereNotNull('for_out_transfer');
		})
			->get();

		foreach ($rows as $row) {

			$ada = false;

			if (count($arrResult) > 0) {
				foreach ($arrResult as $key => $cek) {
					if ($cek['product_id'] == $row->product_id) {
						$ada = true;
						$index = $key;
					}
				}
			}

			if ($ada == true) {
				if ($row->transfer->date >= $request->startDate && $row->transfer->date <= $request->endDate) {
					$arrResult[$index]['qty_used'] = $arrResult[$index]['qty_used'] + $row->qty;
					$arrResult[$index]['total_used'] = $arrResult[$index]['total_used'] + ($row->price() * $row->qty);
				} elseif ($row->transfer->date < $request->startDate) {
					$arrResult[$index]['qty_before'] = $arrResult[$index]['qty_before'] - $row->qty;
					$arrResult[$index]['total_before'] = $arrResult[$index]['total_before'] - ($row->price() * $row->qty);
				}
			} else {
				if ($row->transfer->date >= $request->startDate && $row->transfer->date <= $request->endDate) {
					$arrResult[] = [
						'product_id' 	=> $row->product_id,
						'product_name'	=> $row->product->name(),
						'qty_before'	=> 0,
						'total_before'	=> 0,
						'qty'			=> 0,
						'total'			=> 0,
						'qty_used'		=> 0 + $row->qty,
						'total_used'	=> 0 + ($row->price() * $row->qty),
					];
				} elseif ($row->transfer->date < $request->startDate) {
					$arrResult[] = [
						'product_id' 	=> $row->product_id,
						'product_name'	=> $row->product->name(),
						'qty_before'	=> 0 - $row->qty,
						'total_before'	=> 0 - ($row->price() * $row->qty),
						'qty'			=> 0,
						'total'			=> 0,
						'qty_used'		=> 0,
						'total_used'	=> 0,
					];
				}
			}
		}

		$rows = ProjectSaleReturnProduct::whereHas('projectSaleReturn', function ($query) use ($branch) {
			$query->whereHas('projectSale', function ($query) use ($branch) {
				$query->whereHas('sales', function ($query) use ($branch) {
					$query->where('branch', $branch);
				});
			});
		})
			->get();

		foreach ($rows as $row) {

			$adakuy = false;

			if (count($arrResult) > 0) {
				foreach ($arrResult as $key => $cek) {
					if ($cek['product_id'] == $row->product_id) {
						$adakuy = true;
						$index = $key;
					}
				}
			}

			if ($adakuy == true) {
				if (substr($row->projectSaleReturn->created_at, 0, 10) >= $request->startDate && substr($row->projectSaleReturn->created_at, 0, 10) <= $request->endDate) {
					$arrResult[$index]['qty_used'] = $arrResult[$index]['qty_used'] - $row->qty;
					$arrResult[$index]['total_used'] = $arrResult[$index]['total_used'] - (str_replace(',', '.', str_replace('.', '', $row->purchasePrice())) * $row->qty);
				} elseif (substr($row->projectSaleReturn->created_at, 0, 10) < $request->startDate) {
					$arrResult[$index]['qty_before'] = $arrResult[$index]['qty_before'] + $row->qty;
					$arrResult[$index]['total_before'] = $arrResult[$index]['total_before'] + (str_replace(',', '.', str_replace('.', '', $row->purchasePrice())) * $row->qty);
				}
			} else {
				if (substr($row->projectSaleReturn->created_at, 0, 10) >= $request->startDate && substr($row->projectSaleReturn->created_at, 0, 10) <= $request->endDate) {
					$arrResult[] = [
						'product_id' 	=> $row->product_id,
						'product_name'	=> $row->product->name(),
						'qty_before'	=> 0,
						'price_before'	=> 0,
						'qty'			=> $row->qty,
						'total'			=> str_replace(',', '.', str_replace('.', '', $row->purchasePrice())) * $row->qty,
						'qty_used'		=> 0,
						'price_used'	=> 0
					];
				} elseif (substr($row->projectSaleReturn->created_at, 0, 10) < $request->startDate) {
					$arrResult[] = [
						'product_id' 	=> $row->product_id,
						'product_name'	=> $row->product->name(),
						'qty_before'	=> $row->qty,
						'total_before'	=> str_replace(',', '.', str_replace('.', '', $row->purchasePrice())) * $row->qty,
						'qty'			=> 0,
						'total'			=> 0,
						'qty_used'		=> 0,
						'total_used'	=> 0
					];
				}
			}
		}

		$rows = ProjectPurchaseReturnProduct::whereHas('projectPurchaseReturn', function ($query) use ($branch) {
			$query->whereHas('projectPurchase', function ($query) use ($branch) {
				$query->whereHas('sales', function ($query) use ($branch) {
					$query->where('branch', $branch);
				});
			});
		})
			->get();

		foreach ($rows as $row) {

			$adakuy = false;

			if (count($arrResult) > 0) {
				foreach ($arrResult as $key => $cek) {
					if ($cek['product_id'] == $row->product_id) {
						$adakuy = true;
						$index = $key;
					}
				}
			}

			if ($adakuy == true) {
				if ($row->projectPurchaseReturn->date >= $request->startDate && $row->projectPurchaseReturn->date <= $request->endDate) {
					$arrResult[$index]['qty_used'] = $arrResult[$index]['qty_used'] + $row->qty;
					$arrResult[$index]['total_used'] = $arrResult[$index]['total_used'] + (str_replace(',', '.', str_replace('.', '', $row->purchasePrice())) * $row->qty);
				} elseif ($row->projectPurchaseReturn->date < $request->startDate) {
					$arrResult[$index]['qty_before'] = $arrResult[$index]['qty_before'] - $row->qty;
					$arrResult[$index]['total_before'] = $arrResult[$index]['total_before'] - (str_replace(',', '.', str_replace('.', '', $row->purchasePrice())) * $row->qty);
				}
			} else {
				if ($row->projectPurchaseReturn->date >= $request->startDate && $row->projectPurchaseReturn->date <= $request->endDate) {
					$arrResult[] = [
						'product_id' 	=> $row->product_id,
						'product_name'	=> $row->product->name(),
						'qty_before'	=> 0,
						'total_before'	=> 0,
						'qty'			=> 0,
						'total'			=> 0,
						'qty_used'		=> $row->qty,
						'total_used'	=> (str_replace(',', '.', str_replace('.', '', $row->purchasePrice())) * $row->qty)
					];
				} elseif ($row->projectPurchaseReturn->date < $request->startDate) {
					$arrResult[] = [
						'product_id' 	=> $row->product_id,
						'product_name'	=> $row->product->name(),
						'qty_before'	=> 0 - $row->qty,
						'total_before'	=> 0 - str_replace(',', '.', str_replace('.', '', $row->purchasePrice())) * $row->qty,
						'qty'			=> 0,
						'total'			=> 0,
						'qty_used'		=> 0,
						'total_used'	=> 0
					];
				}
			}
		}

		$array = collect($arrResult)->sortBy('product_name')->toArray();

		return view('admin.pdf.report.inventory.product', [
			'array' 	=> $array,
			'branch'	=> $request->branch,
			'mode'	    => $request->mode,
			'startDate'	=> $request->startDate,
			'endDate'	=> $request->endDate
		]);
	}


	public function printByWarehouse(Request $request)
	{
		$data = [];
		$array = $this->getStock($request);

		foreach ($array as $row) {
			$totalqty = $row['qty_before'] + $row['qty'];
			$finalqty = $totalqty - $row['qty_used'] - $row['qty_adjust'];
			$pricefinal = $finalqty ? ($row['total'] + $row['total_before'] - $row['total_used'] - $row['total_adjust']) / $finalqty : 0;
			$grandtotal = ($row['total'] + $row['total_before'] - $row['total_used'] - $row['total_adjust']);

			$stockWarehouse = Stock::where(function ($query) use ($row, $request) {
				$query->where('product_id', $row['product_id'])->where('branch', $request->branch);
				if ($request->warehouse_id) {
					$query->where('warehouse_id', $request->warehouse_id);
				}
			})->get();

			if ($finalqty > 0) {
				foreach ($stockWarehouse as $rowstock) {

					$index  = $this->checkExist($data, $rowstock->warehouse_id);

					if ($rowstock->qty > 0) {
						if ($index >= 0) {
							$data[$index]['detail'][] = [
								'product_name'   => $row['product_name'],
								'image'			 => $row['image'],
								'size'			 => $row['size'],
								'm2'			 => $row['m2'],
								'thickness'	     => $row['thickness'],
								'qty' 			 => $rowstock->qty > $finalqty ? $finalqty : $rowstock->qty,
								'carton_pcs'	 => $row['carton_pcs'] . 'pcs/ Box',
								'qty_sqm'		 => !is_string($row['m2']) ? ($rowstock->qty > $finalqty ? $finalqty * $row['m2'] : $rowstock->qty * $row['m2']) : $row['m2'],
								'pricefinal'     => $pricefinal,
								'pricefinal_sqm' => !is_string($row['m2']) ? $pricefinal / $row['m2'] : $pricefinal,

							];
						} else {
							$data[] = [
								'warehouse_id'   => $rowstock->warehouse_id,
								'warehouse_name' => $rowstock->warehouse->name,
								'detail' => array(
									[
										'product_name'   => $row['product_name'],
										'image'			 => $row['image'],
										'size'			 => $row['size'],
										'm2'			 => $row['m2'],
										'thickness'	     => $row['thickness'],
										'qty' 			 => $rowstock->qty > $finalqty ? $finalqty : $rowstock->qty,
										'carton_pcs'	 => $row['carton_pcs'] . 'pcs/ Box',
										'qty_sqm'		 => !is_string($row['m2']) ? ($rowstock->qty > $finalqty ? $finalqty * $row['m2'] : $rowstock->qty * $row['m2']) : $row['m2'],
										'pricefinal'     => $pricefinal,
										'pricefinal_sqm' => !is_string($row['m2']) ? $pricefinal / $row['m2'] : $pricefinal,

									]
								)
							];
						}
					}
				}
			}
		}

		return view('admin.pdf.report.inventory.product_by_warehouse', [
			'array' 	=> $array,
			'branch'	=> $request->branch,
			'arrResult'	=> $data,
			'startDate'	=> $request->startDate,
			'endDate'	=> $request->endDate
		]);
	}

	public function printCardMode(Request $request)
	{
		$data = [];

		$products = $this->getStock($request);
		dd($request->warehouse_id);
		foreach ($products as $product) {
			$totalqty = $product['qty_before'] + $product['qty']; //qty now + qty before current date
			$finalqty = $totalqty - $product['qty_used'] - $product['qty_adjust']; // Total qty - qty used and qty adjust
			$stock = Stock::where(function ($query) use ($product, $request) {
				$query->where('product_id', $product['product_id'])->where('branch', $request->branch);
				if ($request->warehouse_id) {
					$query->where('warehouse_id', $request->warehouse_id);
				}
			})->get();
			
			foreach ($stock as $stock) {
				$conditionStock = $request->filter_mode == 2 ? $stock->qty > 0 : $finalqty;
				if ($conditionStock) {
					$index = $this->checkExist($data, $stock->warehouse_id);

					if ($index >= 0) {
						$data[$index]['detail'][] = [
							'name' 		 => $product['product_name'],
							'brand_name' => $product['brand_name'],
							'image' 	 => $product['image'],
							'qty'		 => $stock->qty,
							'size'		 => $product['size'],
						];
					} else {
						$data[] = [
							'warehouse_id'   => $stock->warehouse_id,
							'warehouse_name' => $stock->warehouse->name,
							'detail' => array(
								[
									'name' 		 => $product['product_name'],
									'brand_name' => $product['brand_name'],
									'image' 	 => $product['image'],
									'qty'		 => $stock->qty,
									'size'		 => $product['size'],
								]
							)
						];
					}
				}
			}
		}


		// dd($data);
		return view('admin.pdf.report.inventory.product_card_mode', [
			'data' 	=> $data,
		]);
	}

	public function exportByWarehouse(Request $request)
	{
		$data = [];
		$branch = $request->branch;
		$array = $this->getStock($request);

		foreach ($array as $key => $row) {
			$totalqty = $row['qty_before'] + $row['qty'];
			$finalqty = $totalqty - $row['qty_used'] - $row['qty_adjust'];
			$pricefinal = $finalqty ? ($row['total'] + $row['total_before'] - $row['total_used'] - $row['total_adjust']) / $finalqty : 0;
			$grandtotal = ($row['total'] + $row['total_before'] - $row['total_used'] - $row['total_adjust']);

			if ($finalqty > 0) {
				foreach (Stock::where('product_id', $row['product_id'])->where('branch', $branch)->get() as $key => $rowstock) {

					$index  = $this->checkExist($data, $rowstock->warehouse_id);

					if ($rowstock->qty > 0) {
						if ($index >= 0) {
							$data[$index]['detail'][] = [
								'product_name'   => $row['product_name'],
								'image'			 => $row['image'],
								'size'			 => $row['size'],
								'm2'			 => $row['m2'],
								'thickness'	     => $row['thickness'],
								'qty' 			 => $rowstock->qty > $finalqty ? $finalqty : $rowstock->qty,
								'carton_pcs'	 => $row['carton_pcs'] . 'pcs/ Box',
								'qty_sqm'		 => !is_string($row['m2']) ? ($rowstock->qty > $finalqty ? $finalqty * $row['m2'] : $rowstock->qty * $row['m2']) : $row['m2'],
								'pricefinal'     => $pricefinal,
								'pricefinal_sqm' => !is_string($row['m2']) ? $pricefinal / $row['m2'] : $pricefinal,

							];
						} else {
							$data[] = [
								'warehouse_id'   => $rowstock->warehouse_id,
								'warehouse_name' => $rowstock->warehouse->name,
								'detail' => array(
									[
										'product_name'   => $row['product_name'],
										'image'			 => $row['image'],
										'size'			 => $row['size'],
										'm2'			 => $row['m2'],
										'thickness'	     => $row['thickness'],
										'qty' 			 => $rowstock->qty > $finalqty ? $finalqty : $rowstock->qty,
										'carton_pcs'	 => $row['carton_pcs'] . 'pcs/ Box',
										'qty_sqm'		 => !is_string($row['m2']) ? ($rowstock->qty > $finalqty ? $finalqty * $row['m2'] : $rowstock->qty * $row['m2']) : $row['m2'],
										'pricefinal'     => $pricefinal,
										'pricefinal_sqm' => !is_string($row['m2']) ? $pricefinal / $row['m2'] : $pricefinal,

									]
								)
							];
						}
					}
				}
			}
		}

		$response = [
			'array' 	=> $array,
			'branch'	=> $request->branch,
			'arrResult'	=> $data,
			'startDate'	=> $request->startDate,
			'endDate'	=> $request->endDate
		];

		return Excel::download(new InventoryExport($response), 'inventory.xlsx');
	}


	public function checkExist($data, $val)
	{
		$index = -1;

		foreach ($data as $key => $row) {
			if ($row['warehouse_id'] == $val) {
				$index = $key;
			}
		}

		return $index;
	}


	public function stockdatatable(Request $request)
	{
		$column = [
			'detail',
			'id',
			'product_id',
			'warehouse_id',
			'qty',
			'unit',
			'branch'
		];

		$start  = $request->start;
		$length = $request->length;
		$order  = $column[$request->input('order.0.column')];
		$dir    = $request->input('order.0.dir');
		$search = $request->input('search.value');

		$total_data = Stock::count();

		$query_data = Stock::where(function ($query) use ($search, $request) {
			if ($search) {
				$query->where(function ($query) use ($search) {
					$query->whereHas('product', function ($query) use ($search) {
						$query->whereHas('type', function ($query) use ($search) {
							$query->where('code', 'like', "%$search%")
								->orWhereHas('category', function ($query) use ($search) {
									$query->where('name', 'like', "%$search%");
								})
								->orWhereHas('surface', function ($query) use ($search) {
									$query->where('name', 'like', "%$search%");
								})
								->orWhereHas('color', function ($query) use ($search) {
									$query->where('name', 'like', "%$search%");
								})
								->orWhereHas('pattern', function ($query) use ($search) {
									$query->where('name', 'like', "%$search%");
								});
						});
					})
						->orWhereHas('warehouse', function ($query) use ($search) {
							$query->where('code', 'like', "%$search%")
								->orWhere('name', 'like', "%$search%");
						});
				});
			}

			if ($request->branch) {
				$query->where('branch', $request->branch);
			}
		})
			->offset($start)
			->limit($length)
			->orderBy($order, $dir)
			->get();

		$total_filtered = Stock::where(function ($query) use ($search, $request) {
			if ($search) {
				$query->where(function ($query) use ($search) {
					$query->whereHas('product', function ($query) use ($search) {
						$query->whereHas('type', function ($query) use ($search) {
							$query->where('code', 'like', "%$search%")
								->orWhereHas('category', function ($query) use ($search) {
									$query->where('name', 'like', "%$search%");
								})
								->orWhereHas('surface', function ($query) use ($search) {
									$query->where('name', 'like', "%$search%");
								})
								->orWhereHas('color', function ($query) use ($search) {
									$query->where('name', 'like', "%$search%");
								})
								->orWhereHas('pattern', function ($query) use ($search) {
									$query->where('name', 'like', "%$search%");
								});
						});
					})
						->orWhereHas('warehouse', function ($query) use ($search) {
							$query->where('code', 'like', "%$search%")
								->orWhere('name', 'like', "%$search%");
						});
				});
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

				$response['data'][] = [
					$nomor,
					$val->product->name(),
					isset($val->warehouse->code) ? $val->warehouse->code . ' - ' . $val->warehouse->name : '',
					$val->qty,
					$val->unit(),
					$val->branch(),
					'<a onclick="showStockCards(' . $val->id . ')" href="javascript:void(0);" class="btn btn-sm bg-info"><i class="icon-calculator2"></i></a>'
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

	public function card(Request $request)
	{
		$data = Stock::find($request->id);

		$rowIn   = ProjectWarehouseProduct::where(function ($query) use ($data) {
			$query->whereHas('projectWarehouse', function ($query) use ($data) {
				$query->where('warehouse_id', $data->warehouse_id);
			});
		})
			->where('product_id', $data->product_id)
			->get();

		$rowOut   = ProjectDeliveryProduct::where(function ($query) use ($data) {
			$query->whereHas('projectDelivery', function ($query) use ($data) {
				$query->where('warehouse_id', $data->warehouse_id)
					->whereNotNull('received_date');
			});
		})
			->where('product_id', $data->product_id)
			->get();

		$rowInReturn   = ProjectSaleReturnProduct::where(function ($query) use ($data) {
			$query->whereHas('projectSaleReturn', function ($query) use ($data) {
				$query->where('warehouse_id', $data->warehouse_id);
			});
		})
			->where('product_id', $data->product_id)
			->get();

		$rowOutReturn   = ProjectPurchaseReturnProduct::where(function ($query) use ($data) {
			$query->whereHas('projectPurchaseReturn', function ($query) use ($data) {
				$query->where('warehouse_id', $data->warehouse_id);
			});
		})
			->where('product_id', $data->product_id)
			->get();

		$rowInTransfer   = TransferProduct::where(function ($query) use ($data) {
			$query->whereHas('transfer', function ($query) use ($data) {
				$query->where('to_warehouse_id', $data->warehouse_id);
			});
		})
			->where('product_id', $data->product_id)
			->get();

		$rowOutTransfer   = TransferProduct::where(function ($query) use ($data) {
			$query->whereHas('transfer', function ($query) use ($data) {
				$query->where('from_warehouse_id', $data->warehouse_id);
			});
		})
			->where('product_id', $data->product_id)
			->get();

		$data = [
			'title'				=> 'Stock Card',
			'product_name'	    => $data->product->name(),
			'rowIn'				=> $rowIn,
			'rowOut'			=> $rowOut,
			'rowInReturn'		=> $rowInReturn,
			'rowInTransfer'		=> $rowInTransfer,
			'rowOutReturn'		=> $rowOutReturn,
			'rowOutTransfer'	=> $rowOutTransfer,

		];

		return view('admin.pdf.report.inventory.stock_card', $data);
	}
}
