<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use App\Models\Cogs;
use App\Models\Emkl;
use App\Models\Import;
use App\Models\Freight;
use App\Models\Product;
use App\Models\Currency;
use App\Models\CurrencyRate;
use App\Models\PricingPolicy;
use App\Models\MarketingStructure;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CogsController extends Controller {

    public function index()
    {
        $data = [
            'title'   => 'COGS Sales',
            'content' => 'admin.master_data.cogs_master.cogs_sales'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request) 
    {
        $column = [
            'id',
            'product_id',
            'cogs',
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');
		
		$total_data = Product::count();
        
        $query_data = Product::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->whereHas('type', function($query) use ($search) {
                                    $query->whereRaw("code LIKE '%$search%'")
                                        ->orWhereHas('category', function($query) use ($search) {
                                                $query->whereRaw("name LIKE '%$search%'");
                                            })
                                        ->orWhereHas('color', function($query) use ($search) {
                                                $query->whereRaw("name LIKE '%$search%'");
                                            });
                                });
                        })
                        ->orWhereHas('brand', function($query) use ($search) {
                                $query->whereRaw("name LIKE '%$search%'");
                            })
                        ->orWhereHas('country', function($query) use ($search) {
                                $query->whereRaw("name LIKE '%$search%'");
                            })
						->orWhereHas('cogs', function($query) use ($search) {
                                $query->whereRaw("updated_at LIKE '%$search%'");
                            });
                }         

            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->groupBy('id')
            ->get();

        $total_filtered = Product::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                            $query->whereHas('type', function($query) use ($search) {
                                    $query->whereRaw("code LIKE '%$search%'")
                                        ->orWhereHas('category', function($query) use ($search) {
                                                $query->whereRaw("name LIKE '%$search%'");
                                            })
                                        ->orWhereHas('color', function($query) use ($search) {
                                                $query->whereRaw("name LIKE '%$search%'");
                                            });
                                });
                        })
                        ->orWhereHas('brand', function($query) use ($search) {
                                $query->whereRaw("name LIKE '%$search%'");
                            })
                        ->orWhereHas('country', function($query) use ($search) {
                                $query->whereRaw("name LIKE '%$search%'");
                            })
						->orWhereHas('cogs', function($query) use ($search) {
                                $query->whereRaw("updated_at LIKE '%$search%'");
                            });
                }         

            })
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
                $response['data'][] = [
                    $nomor,
                    $val->name(),
                    isset($val->cogs->updated_at) ? date('Y-m-d H:i:s',strtotime($val->cogs->updated_at)) : '<span class="badge badge-danger">Empty</span>',
                    $val->checkCogs()
                ];

                $nomor++;
            }
        }

        $response['recordsTotal'] = 0;
        if($total_data <> FALSE) {
            $response['recordsTotal'] = $total_data;
        }

        $response['recordsFiltered'] = 0;
        if($total_filtered <> FALSE) {
            $response['recordsFiltered'] = $total_filtered;
        }

        return response()->json($response);
    }

    public function getCompleteData(Request $request)
    {
		$import_id = $request->import_id ? $request->import_id : '1';
		
        $data          = Product::find($request->product_id);
        $currency_rate = CurrencyRate::where('currency_id', $request->currency_id)
            ->where('company_id', $data->company_id)
            ->latest()
            ->limit(1)
            ->get();

        $emkl = Emkl::where('company_id', $data->company_id)
            ->where('country_id', $data->country_id)
            ->where('container', $data->container_standart)
			->where('import_id',$import_id)
            ->first();

        if($emkl) {
            $container = $emkl->container; 
            $c         = $emkl->container();
            $lcc       = $emkl->cost;
        } else {
            $container = 0;
            $c         = 'Not Set';
            $lcc       = 0;
        }

        $freight = Freight::where('country_id', $data->country_id)
            ->where('container', $container)
            ->where('shipping', $request->shipping)
            ->where('city_id', $request->city_id)
            ->first();

        if($data->currencyPrice->count() > 0) {
            $pp = $data->currencyPrice->last()->price;
        } else {
            $pp = 0;
        }

        if($currency_rate->count() > 0) {
            $symbol = $currency_rate[0]->currency->symbol;
            $ru     = $currency_rate[0]->conversion;
        } else {
            $symbol = null;
            $ru     = 0;
        }

        if($freight) {
            $fcu = $freight->cost;
        } else {
            $fcu = 0;
        }
        
		$mkt = MarketingStructure::where('company_id',$data->company_id)->latest()->first();
		
		if($mkt){
			$mkt_sco = $mkt->sales_commission;
			$mkt_iip = $mkt->interest_in_payment;
			$mkt_stc = $mkt->travel_sales_cost;
			$mkt_mc = $mkt->marketing_cost;
			$mkt_mf = $mkt->middlemant_commission;
			$mkt_pc = $mkt->project_commission;
			$mkt_osc = $mkt->on_site_cost;
			$mkt_sc = $mkt->storage_cost;
			$mkt_rc = $mkt->rental_cost;
			$mkt_fc = $mkt->fixed_cost;
			$mkt_iob = $mkt->interest_on_buying;
			$mkt_s = $mkt->saving;
			$mkt_rp = $mkt->nett_profit;
		}else{
			$mkt_sc = 0;
			$mkt_iip = 0;
			$mkt_stc = 0;
			$mkt_mc = 0;
			$mkt_mf = 0;
			$mkt_pc = 0;
			$mkt_osc = 0;
			$mkt_sc = 0;
			$mkt_rc = 0;
			$mkt_fc = 0;
			$mkt_iob = 0;
			$mkt_s = 0;
			$mkt_rp = 0;
		}
		
		
		
		if($import_id == 3){
			if($data->container_standart == '1'){
				$cs = 30;
			}elseif($data->container_standart == '2'){
				$cs = 60;
			}
			
			$aou  = $request->agent_fee_usd ? $request->agent_fee_usd : 0;
			$lcd  = 0;
			$nc   = $request->number_container ? $request->number_container : 1;
			$ppc  = $request->price_profile_custom ? $request->price_profile_custom : 0;
			$sc   = $request->sni_cost ? $request->sni_cost : 0;
			$sg   = 0;
			$l    = $data->type->length ? $data->type->length : 0;
			$wd   = $data->type->width ? $data->type->width : 0;
			$cp   = 1;
			$t    = $data->type->height ? $data->type->height : 0;
			$wg   = $data->type->weight ? $data->type->weight : 0;
			$cu   = $data->type->conversion;
			$lpi  = $ru * $cu * $pp;
			$tsl  = 0;
			$afus = 0;
			$afi  = 0;
			$cc   = (($l * $wd * $t) / 1000000000) / $cs;
			$twc  = 0;
			$toc  = 0;
			$sd   = ($l / 100) * ($wd / 100) * $cp;
			$fc   = @($fcu * $ru * $cc);
			$tlc  = @($lcc * $toc / $sd);
			$lcs  = @($lcd * $ru * $toc / $sd / $nc);
			$id   = 0;
			$vt   = 0;
			$it   = 0;
			$tit  = 0;
			$ci   = $lpi + $fc + ($lcc * $cc) + $sc;
			$cpi  = $ci * 1.1;
			$csi  = $cpi * 1.05;
			$rental_cost = ($mkt_rc / 100) * $csi;
			$fixed_cost = ($mkt_fc / 100) * $csi;
			$interest_buying = ($mkt_iob / 100) * $csi;
			$saving = ($mkt_s / 100) * $csi;
			$rsv_profit = ($mkt_rp / 100) * $csi;
			$cogs_marketing_1 = $csi + $rental_cost + $fixed_cost + $interest_buying + $saving + $rsv_profit;
			$sales_commission = ($mkt_sco / 100) * $request->recommended_price;
			$interest_payment = ($mkt_iip / 100) * $csi;
			$sales_travel_cost = ($mkt_stc / 100) * $csi;
			$marketing_cost = ($mkt_mc / 100) * $csi;
			$middleman_fee = ($mkt_mf / 100) * $csi;
			$project_commission = ($mkt_pc / 100) * $csi;
			$on_site_cost = ($mkt_osc / 100) * $csi;
			$storage_cost = ($mkt_sc / 100) * $csi;
			$bottom_price = $cogs_marketing_1 + $sales_commission + $interest_payment + $sales_travel_cost + $marketing_cost + $middleman_fee + $project_commission + $on_site_cost + $storage_cost;
		}elseif($import_id == 1){
			$cs   = 0;
			$aou  = 0;
			$lcd  = 0;
			$nc   = 0;
			$ppc  = 0;
			$sc   = 0;
			$sg   = 0;
			$l    = $data->type->length ? $data->type->length : 0;
			$wd   = $data->type->width ? $data->type->width : 0;
			$cp   = $data->carton_pcs ? $data->carton_pcs : 0;
			$t    = $data->type->thickness ? $data->type->thickness : 0;
			$wg   = $data->type->weight ? $data->type->weight : 0;
			$cu   = $data->type->conversion;
			$lpi  = 0;
			$tsl  = 0;
			$afus = 0;
			$afi  = 0;
			$cc   = 0;
			$twc  = 0;
			$toc  = 0;
			$sd   = 0;
			$fc   = 0;
			$tlc  = 0;
			$lcs  = 0;
			$id   = 0;
			$vt   = 0;
			$it   = 0;
			$tit  = 0;
			$ci   = 0;
			$cpi  = $pp;
			$csi  = $cpi * 1.05;
			$rental_cost = ($mkt_rc / 100) * $csi;
			$fixed_cost = ($mkt_fc / 100) * $csi;
			$interest_buying = ($mkt_iob / 100) * $csi;
			$saving = ($mkt_s / 100) * $csi;
			$rsv_profit = ($mkt_rp / 100) * $csi;
			$cogs_marketing_1 = $csi + $rental_cost + $fixed_cost + $interest_buying + $saving + $rsv_profit;
			$sales_commission = ($mkt_sco / 100) * $request->recommended_price;
			$interest_payment = ($mkt_iip / 100) * $csi;
			$sales_travel_cost = ($mkt_stc / 100) * $csi;
			$marketing_cost = ($mkt_mc / 100) * $csi;
			$middleman_fee = ($mkt_mf / 100) * $csi;
			$project_commission = ($mkt_pc / 100) * $csi;
			$on_site_cost = ($mkt_osc / 100) * $csi;
			$storage_cost = ($mkt_sc / 100) * $csi;
			$bottom_price = $cogs_marketing_1 + $sales_commission + $interest_payment + $sales_travel_cost + $marketing_cost + $middleman_fee + $project_commission + $on_site_cost + $storage_cost;
		}else{
			$cs   = $data->container_stock ? $data->container_stock : 0;
			$aou  = $request->agent_fee_usd ? $request->agent_fee_usd : 0;
			$lcd  = $request->ls_cost_document ? $request->ls_cost_document : 0;
			$nc   = $request->number_container ? $request->number_container : 0;
			$ppc  = $request->price_profile_custom ? $request->price_profile_custom : 0;
			$sc   = $request->sni_cost ? $request->sni_cost : 0;
			$l    = $data->type->length ? $data->type->length : 0;
			$wd   = $data->type->width ? $data->type->width : 0;
			$cp   = $data->carton_pcs ? $data->carton_pcs : 0;
			$t    = $data->type->thickness ? $data->type->thickness : 0;
			$wg   = $data->type->weight ? $data->type->weight : 0;
			$cu   = $data->type->conversion;
			$lpi  = $ru * $cu * $pp;
			$tsl  = ($l / 100) * ($wd / 100) * $cp * $cs;
			$afus = @($aou  / $tsl);
			$afi  = @($afus * $cu * $ru);
			$cc   = ($l / 100) * ($wd / 100) * ($t / 1000) * $cp;
			$twc  = $wg * $cs;
			$toc  = $wg / $twc;
			$sd   = ($l / 100) * ($wd / 100) * $cp;
			$fc   = @($fcu * $ru * $toc / $sd);
			$sg   = (($ppc * $ru) + $fc)  * 0.19;
			$tlc  = @($lcc * $toc / $sd);
			$lcs  = @($lcd * $ru * $toc / $sd / $nc);
			$id   = (($ppc * $ru) + $fc) * 0.05;
			$vt   = (($request->price_profile_custom * $ru) + $id + $sg + $fc) * 0.1;
			$it   = (($request->price_profile_custom * $ru) + $id + $sg + $fc) * 0.075;
			$tit  = $vt + $id + $it + $sg;
			$ci   = $lpi + $afi + $fc + $tlc + $lcs + $tit + $sc;
			$cpi  = $ci * 1.1;
			$csi  = $cpi * 1.05;
			$rental_cost = ($mkt_rc / 100) * $csi;
			$fixed_cost = ($mkt_fc / 100) * $csi;
			$interest_buying = ($mkt_iob / 100) * $csi;
			$saving = ($mkt_s / 100) * $csi;
			$rsv_profit = ($mkt_rp / 100) * $csi;
			$cogs_marketing_1 = $csi + $rental_cost + $fixed_cost + $interest_buying + $saving + $rsv_profit;
			$sales_commission = ($mkt_sco / 100) * $request->recommended_price;
			$interest_payment = ($mkt_iip / 100) * $csi;
			$sales_travel_cost = ($mkt_stc / 100) * $csi;
			$marketing_cost = ($mkt_mc / 100) * $csi;
			$middleman_fee = ($mkt_mf / 100) * $csi;
			$project_commission = ($mkt_pc / 100) * $csi;
			$on_site_cost = ($mkt_osc / 100) * $csi;
			$storage_cost = ($mkt_sc / 100) * $csi;
			$bottom_price = $cogs_marketing_1 + $sales_commission + $interest_payment + $sales_travel_cost + $marketing_cost + $middleman_fee + $project_commission + $on_site_cost + $storage_cost;
		}

        return response()->json([
            'origin_country'         => $data->country->name,
            'lengths'                => number_format($l, 2, ',', '.') . ' Cm',
            'width'                  => number_format($wd, 2, ',', '.') . ' Cm',
            'pcs_ctn'                => number_format($cp, 2, ',', '.') . ' <sub>/ carton</sub>',
            'thickness'              => number_format($t, 2, ',', '.') . ' mm',
            'min_total_dos'          => number_format($cs, 2, ',', '.') . ' <sub>/ container</sub>',
            'container'              => $c,
            'product_price'          => $symbol . (!is_nan($pp) && !is_infinite($pp) ? number_format($pp, 2, ',', '.') : 0),
            'buying_unit'            => $data->type->buyUnit->code,
            'selling_unit'           => $data->type->sellingUnit->code,
            'conversion_unit'        => $symbol . (!is_nan($cu) && !is_infinite($cu) ? number_format($cu, 2, ',', '.') : 0),
            'rate_unit'              => 'Rp' . (!is_nan($ru) && !is_infinite($ru) ? number_format($ru, 2, ',', '.') : 0),
            'local_price_idr'        => 'Rp' . (!is_nan($lpi) && !is_infinite($lpi) ? number_format($lpi, 2, ',', '.') : 0),
            'total_sqm_load'         => (!is_nan($tsl) && !is_infinite($tsl) ? number_format(round($tsl), 2, ',', '.') : 0) . ' <sub>/ container</sub>',
            'agent_fee_usd_sqm'      => '$' . (!is_nan($afus) && !is_infinite($afus) ? number_format($afus, 3, ',', '.') : 0) . ' <sub>/ SQM</sub>',
            'agent_fee_idr'          => 'Rp' . (!is_nan($afi) && !is_infinite($afi) ? number_format($afi, 2, ',', '.') : 0),
            'freight_cost_usd'       => '$' . (!is_nan($fcu) && !is_infinite($fcu) ? number_format($fcu, 2, ',', '.') : 0) . ' <sub>/ container</sub>',
            'cbm_container'          => (!is_nan($cc) && !is_infinite($cc) ? number_format($cc, 6, ',', '.') : 0) . ' <sub>/ container</sub>',
            'kg_dos'                 => number_format($wg, 2, ',', '.') . ' Kg',
            'total_weight_container' => (!is_nan($twc) && !is_infinite($twc) ? number_format(round($twc), 2, ',', '.') : 0) . ' <sub>/ container</sub>',
            'tonnage_of_container'   => number_format($toc * 100,5,',','.') . '%',
            'sqm_dos'                => (!is_nan($sd) && !is_infinite($sd) ? number_format($sd, 3, ',', '.') : 0) . ' <sub>/ DOS</sub>',
            'freight_cost'           => 'Rp' . (!is_nan($fc) && !is_infinite($fc) ? number_format(round($fc), 2, ',', '.') : 0),
            'landed_cost_container'  => 'Rp' . (!is_nan($lcc) && !is_infinite($lcc) ? number_format($lcc, 2, ',', '.') : 0) . ' <sub>/ container</sub>',
            'total_landed_cost'      => 'Rp' . (!is_nan($tlc) && !is_infinite($tlc) ? number_format(round($tlc), 2, ',', '.') : 0) . ' <sub>/ SQM</sub>',
            'rate_of_usd'            => 'Rp' . (!is_nan($ru) && !is_infinite($ru) ? number_format($ru, 2, ',', '.') : 0),
            'ls_cost_sqm'            => 'Rp' . (!is_nan($lcs) && !is_infinite($lcs) ? number_format(round($lcs), 2, ',', '.') : 0) . ' <sub>/ SQM</sub>',
            'import_duty'            => 'Rp' . (!is_nan($id) && !is_infinite($id) ? number_format(round($id), 2, ',', '.') : 0) . ' <sub>/ SQM</sub>',
            'value_tax'              => 'Rp' . (!is_nan($vt) && !is_infinite($vt) ? number_format(round($vt), 2, ',', '.') : 0) . ' <sub>/ SQM</sub>',
            'income_tax'             => 'Rp' . (!is_nan($it) && !is_infinite($it) ? number_format(round($it), 2, ',', '.') : 0) . ' <sub>/ SQM</sub>',
            'total_import_tax'       => 'Rp' . (!is_nan($tit) && !is_infinite($tit) ? number_format(round($tit), 2, ',', '.') : 0) . ' <sub>/ SQM</sub>',
            'safe_guard'             => 'Rp' . (!is_nan($sg) && !is_infinite($sg) ? number_format($sg, 2, ',', '.') : 0) . ' <sub>/ SQM</sub>',
            'cogs_idr'               => 'Rp' . (!is_nan($ci) && !is_infinite($ci) ? number_format($ci, 2, ',', '.') : 0) . ' <sub>/ SQM</sub>',
            'cogs_pta_idr'           => 'Rp' . (!is_nan($cpi) && !is_infinite($cpi) ? number_format($cpi, 2, ',', '.') : 0) . ' <sub>/ SQM</sub>',
            'cogs_smb_idr'           => 'Rp' . (!is_nan($csi) && !is_infinite($csi) ? number_format($csi, 2, ',', '.') : 0) . ' <sub>/ SQM</sub>',
			'rental_cost'			 => (!is_nan($rental_cost) && !is_infinite($rental_cost) ? round($rental_cost,2) : 0),
			'fixed_cost'			 => (!is_nan($fixed_cost) && !is_infinite($fixed_cost) ? round($fixed_cost,2) : 0),
			'interest_buying'		 => (!is_nan($interest_buying) && !is_infinite($interest_buying) ? round($interest_buying,2) : 0),
			'saving'				 => (!is_nan($saving) && !is_infinite($saving) ? round($saving,2) : 0),
			'rsv_profit'			 => (!is_nan($rsv_profit) && !is_infinite($rsv_profit) ? round($rsv_profit,2) : 0),
			'cogs_marketing_1'		 => (!is_nan($cogs_marketing_1) && !is_infinite($cogs_marketing_1) ? round($cogs_marketing_1,2) : 0),
			'sales_commission'		 => (!is_nan($sales_commission) && !is_infinite($sales_commission) ? round($sales_commission,2) : 0),
			'interest_payment'		 => (!is_nan($interest_payment) && !is_infinite($interest_payment) ? round($interest_payment,2) : 0),
			'sales_travel_cost'		 => (!is_nan($sales_travel_cost) && !is_infinite($sales_travel_cost) ? round($sales_travel_cost,2) : 0),
			'marketing_cost'		 => (!is_nan($marketing_cost) && !is_infinite($marketing_cost) ? round($marketing_cost,2) : 0),
			'middleman_fee'			 => (!is_nan($middleman_fee) && !is_infinite($middleman_fee) ? round($middleman_fee,2) : 0),
			'project_commission'	 => (!is_nan($project_commission) && !is_infinite($project_commission) ? round($project_commission,2) : 0),
			'on_site_cost'			 => (!is_nan($on_site_cost) && !is_infinite($on_site_cost) ? round($on_site_cost,2) : 0),
			'storage_cost'			 => (!is_nan($storage_cost) && !is_infinite($storage_cost) ? round($storage_cost,2) : 0),
			'bottom_price'			 => (!is_nan($bottom_price) && !is_infinite($bottom_price) ? round($bottom_price,2) : 0),
        ]);
    }

    public function create(Request $request)
    {
        if($request->has('_token') && session()->token() == $request->_token) {
            $validation = Validator::make($request->all(), [
                'product_id'           => 'required',
                'currency_id'          => 'required',
                'city_id'              => 'required',
                'import_id'            => 'required',
                'price_profile_custom' => 'required',
                'agent_fee_usd'        => 'required',
                'shipping'             => 'required',
                'ls_cost_document'     => 'required',
                'number_container'     => 'required',
                'sni_cost'             => 'required',
				'rental_cost'            	=> 'required',
				'fixed_cost'               	=> 'required',
				'interest_buying'           => 'required',
				'saving'                   	=> 'required',
				'rsv_profit'              	=> 'required',
				'sales_commission'         	=> 'required',
				'interest_payment'          => 'required',
				'sales_travel_cost'        	=> 'required',
				'marketing_cost'           	=> 'required',
				'middleman_fee'             => 'required',
				'project_commission'        => 'required',
				'on_site_cost'             	=> 'required',
				'storage_cost'             	=> 'required',
				'bottom_price'             	=> 'required',
				'price_list'               	=> 'required',
				'recommended_price'         => 'required',
				'store_price_list'         	=> 'required',
				'discount_retail_sales'    	=> 'required',
				'discount_retail_manager'  	=> 'required',
				'discount_retail_director' 	=> 'required'
            ], [
                'product_id.required'           => 'Please select a product.',
                'currency_id.required'          => 'Please select a unit currency.',
                'city_id.required'              => 'Please select a destination port.',
                'import_id.required'            => 'Please select a import.',
                'shipping.required'             => 'Please select a shipping.',
                'price_profile_custom.required' => 'Price profile custom cannot be empty.',
                'agent_fee_usd.required'        => 'Agent fee cannot be empty.',
                'ls_cost_document.required'     => 'ls cost document cannot be empty.',
                'container_stock.required'      => 'Container stock cannot be empty.',
                'number_container.required'     => 'Number container cannot be empty.',
                'sni_cost.required'             => 'SNI cost cannot be empty.',
				'rental_cost.required'            	=> 'Rental cost cannot be empty.',
				'fixed_cost.required'               => 'Fixed cost cannot be empty.',
				'interest_buying.required'          => 'Interest on buying capital cannot be empty.',
				'saving.required'                   => 'Savings cannot be empty.',
				'rsv_profit.required'              	=> 'Rsv profit cannot be empty.',
				'sales_commission.required'         => 'Sales commission cannot be empty.',
				'interest_payment.required'         => 'Interest in payment method cannot be empty.',
				'sales_travel_cost.required'        => 'Sales travel cost cannot be empty.',
				'marketing_cost.required'           => 'Marketing cost cannot be empty.',
				'middleman_fee.required'            => 'Middleman fee cannot be empty.',
				'project_commission.required'       => 'Project commission cannot be empty.',
				'on_site_cost.required'             => 'On site cost cannot be empty.',
				'storage_cost.required'             => 'Storage cost cannot be empty.',
				'bottom_price.required'             => 'Bottom price cannot be empty.',
				'price_list.required'               => 'Price list cannot be empty.',
				'recommended_price.required'        => 'Recommended price cannot be empty.',
				'store_price_list.required'         => 'Store price list cannot be empty.',
				'discount_retail_sales.required'    => 'Discount retail sales cannot be empty.',
				'discount_retail_manager.required'  => 'Discount retail manager cannot be empty.',
				'discount_retail_director.required' => 'Discount retail director cannot be empty.'
            ]);

            if($validation->fails()) {
                return redirect()->back()->withErrors($validation)->withInput();
            } else {
				Cogs::where('product_id',$request->product_id)->delete();
				PricingPolicy::where('product_id',$request->product_id)->delete();
				
                $query = Cogs::create([
                    'product_id'           => $request->product_id,
                    'currency_id'          => $request->currency_id,
                    'city_id'              => $request->city_id,
                    'import_id'            => $request->import_id,
                    'price_profile_custom' => $request->price_profile_custom,
                    'agent_fee_usd'        => $request->agent_fee_usd,
                    'shipping'             => $request->shipping,
                    'ls_cost_document'     => $request->ls_cost_document,
                    'number_container'     => $request->number_container,
                    'sni_cost'             => $request->sni_cost
                ]);
				
				$query2 = PricingPolicy::create([
                    'product_id'               => $request->product_id,
					'rental_cost'              => $request->rental_cost,
					'fixed_cost'               => $request->fixed_cost,
					'interest_buying'          => $request->interest_buying,
					'saving'                   => $request->saving,
					'rsv_profit'               => $request->rsv_profit,
					'sales_commission'         => $request->sales_commission,
					'interest_payment'         => $request->interest_payment,
					'sales_travel_cost'        => $request->sales_travel_cost,
					'marketing_cost'           => $request->marketing_cost,
					'middleman_fee'            => $request->middleman_fee,
					'project_commission'       => $request->project_commission,
					'on_site_cost'             => $request->on_site_cost,
					'storage_cost'             => $request->storage_cost,
					'bottom_price'             => $request->bottom_price,
					'price_list'               => $request->price_list,
					'recommended_price'        => $request->recommended_price,
					'store_price_list'         => $request->store_price_list,
					'discount_retail_sales'    => $request->discount_retail_sales,
					'discount_retail_manager'  => $request->discount_retail_manager,
					'discount_retail_director' => $request->discount_retail_director
                ]);

                if($query) {
                    activity()
                        ->performedOn(new Cogs())
                        ->causedBy(session('bo_id'))
                        ->withProperties($query)
                        ->log('Add price cogs data');
						
					activity()
                        ->performedOn(new Cogs())
                        ->causedBy(session('bo_id'))
                        ->withProperties($query2)
                        ->log('Add price policy data');

                    return redirect()->back()->with(['success' => 'Data added successfully.']);
                } else {
                    return redirect()->back()->withInput()->with(['failed' => 'Data failed to added.']);
                }
            }
        } else {
            $data = [
                'title'    		=> 'Create New COGS Sales',
                'currency' 		=> Currency::where('status', 1)->get(),
                'city'     		=> City::all(),
                'import'   		=> Import::all(),
				'product_id' 	=> $request->product_id ? $request->product_id : '',
				'name' 			=> $request->name ? $request->name : '',
                'content'  		=> 'admin.master_data.cogs_master.cogs_sales_create'
            ];

            return view('admin.layouts.index', ['data' => $data]);
        }
    }

    public function show(Request $request)
    {
        $data     = Cogs::find($request->id);
        $formula  = $data->formula();
        $currency = $data->currency->code;
        $symbol   = $data->currency->symbol;

        return response()->json([
            'product'                => $data->product->name(),
            'currency'               => $currency,
            'city'                   => $data->city->name,
            'import'                 => $data->import->name,
            'price_profile_custom'   => $symbol . number_format($data->price_profile_custom, 2, ',', '.'),
            'agent_fee_usd'          => '$' . number_format($data->agent_fee_usd, 2, ',', '.'),
            'shipping'               => $data->shipping(),
            'ls_cost_document'       => 'Rp' . number_format($data->ls_cost_document, 2, ',', '.'),
            'number_container'       => $data->number_container,
            'sni_cost'               => 'Rp' . number_format($data->sni_cost, 2, ',', '.'),
            'origin_country'         => $data->product->country->name,
            'lengths'                => number_format($formula->lengths, 2, ',', '.') . ' Cm',
            'width'                  => number_format($formula->width, 2, ',', '.') . ' Cm',
            'pcs_ctn'                => number_format($formula->pcs_ctn, 2, ',', '.') . ' <sub>/ carton</sub>',
            'thickness'              => number_format($formula->thickness, 2, ',', '.') . ' mm',
            'min_total_dos'          => number_format($formula->min_total_dos, 2, ',', '.') . ' <sub>/ container</sub>',
            'container'              => $formula->container,
            'product_price'          => $symbol . number_format($formula->product_price, 2, ',', '.'),
            'buying_unit'            => $data->product->type->buyUnit->code,
            'selling_unit'           => $data->product->type->sellingUnit->code,
            'conversion_unit'        => $symbol . number_format($formula->conversion_unit, 2, ',', '.'),
            'rate_unit'              => 'Rp' . number_format($formula->rate_unit, 2, ',', '.'),
            'local_price_idr'        => 'Rp' . number_format($formula->local_price_idr, 2, ',', '.'),
            'total_sqm_load'         => number_format($formula->total_sqm_load, 2, ',', '.') . ' <sub>/ container</sub>',
            'agent_fee_usd_sqm'      => '$' . number_format($formula->agent_fee_usd_sqm, 3, ',', '.') . ' <sub>/ SQM</sub>',
            'agent_fee_idr'          => 'Rp' . number_format($formula->agent_fee_idr, 2, ',', '.'),
            'freight_cost_usd'       => '$' . number_format($formula->freight_cost_usd, 2, ',', '.') . ' <sub>/ container</sub>',
            'cbm_container'          => number_format($formula->cbm_container, 6, ',', '.') . ' <sub>/ container</sub>',
            'kg_dos'                 => number_format($formula->kg_dos, 2, ',', '.') . ' Kg',
            'total_weight_container' => number_format($formula->total_weight_container, 2, ',', '.') . ' <sub>/ container</sub>',
            'tonnage_of_container'   => number_format($formula->tonnage_of_container * 100, 5, ',', '.') . '%',
            'sqm_dos'                => number_format($formula->sqm_dos, 3, ',', '.') . ' <sub>/ DOS</sub>',
            'freight_cost'           => 'Rp' . number_format($formula->freight_cost, 2, ',', '.'),
            'landed_cost_container'  => 'Rp' . number_format($formula->landed_cost_container, 2, ',', '.') . ' <sub>/ CONTAINER</sub>',
            'total_landed_cost'      => 'Rp' . number_format($formula->total_landed_cost, 2, ',', '.') . ' <sub>/ SQM</sub>',
            'rate_of_usd'            => 'Rp' . number_format($formula->rate_of_usd, 2, ',', '.'),
            'ls_cost_sqm'            => 'Rp' . number_format($formula->ls_cost_sqm, 2, ',', '.') . ' <sub>/ SQM</sub>',
            'import_duty'            => 'Rp' . number_format($formula->import_duty, 2, ',', '.') . ' <sub>/ SQM</sub>',
            'value_tax'              => 'Rp' . number_format($formula->value_tax, 2, ',', '.') . ' <sub>/ SQM</sub>',
            'income_tax'             => 'Rp' . number_format($formula->income_tax, 2, ',', '.') . ' <sub>/ SQM</sub>',
            'total_import_tax'       => 'Rp' . number_format($formula->total_import_tax, 2, ',', '.') . ' <sub>/ SQM</sub>',
            'safe_guard'             => 'Rp' . number_format($formula->safe_guard, 2, ',', '.') . ' <sub>/ SQM</sub>',
            'cogs_idr'               => 'Rp' . number_format($formula->cogs_idr, 2, ',', '.') . ' <sub>/ SQM</sub>',
            'cogs_pta_idr'           => 'Rp' . number_format($formula->cogs_pta_idr, 2, ',', '.')  . ' <sub>/ SQM</sub>',
            'cogs_smb_idr'           => 'Rp' . number_format($formula->cogs_smb_idr, 2, ',', '.') . ' <sub>/ SQM</sub>'
        ]);
    }

}
