<style>
    td:first-child,
    .fixed {
        position: sticky;
        left: 0px;
        background-color: inherit;
    }

    tbody {
        display: block;
        height: 600px;
        overflow: auto;
    }

    tbody::-webkit-scrollbar {
        display: none;
    }

    tbody {
        -ms-overflow-style: none;
        /* IE and Edge */
        scrollbar-width: none;
        /* Firefox */
    }

    thead,
    tbody tr,
    tfoot tr {
        display: table;
        width: 100%;
        table-layout: fixed;
        /* even columns width , fix width of table too*/
    }

    thead {
        width: calc(100% - 0em)
            /* scrollbar is average 1em/16px width, remove it from thead width */
    }
</style>
<div class="content-wrapper">
    <div class="page-header page-header-light sidebar-sticky">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4>
                    <i class="icon-arrow-left52 mr-2"></i>
                    <span class="font-weight-semibold">Cash Flow</span>
                </h4>
            </div>
        </div>
        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>
                        Dashboard</a>
                    <a href="javascript:void(0);" class="breadcrumb-item">Report</a>
                    <a href="javascript:void(0);" class="breadcrumb-item">Accounting</a>
                    <span class="breadcrumb-item active">Cash Flow</span>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="card">
            <div class="card-header header-elements-inline">
                <h2 class="card-title font-weight-bold">Filter</h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Branch :</label>
                            <select name="filter_branch" id="filter_branch" class="form-control">
                            @foreach (DB::table('company_entities')->get() as $company)
								<option value="{{$company->id}}">{{$company->name}}</option>
							@endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Date :</label>
                            <div class="input-group">
                                <input type="date" name="filter_start_date" id="filter_start_date" class="form-control">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">To</span>
                                </div>
                                <input type="date" name="filter_finish_date" id="filter_finish_date"
                                    class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div class="input-group">
                                <button type="button" onclick="generate()" class="btn bg-purple mr-2"><i
                                        class="icon-filter4"></i> Search</button>
                                <button type="button" onclick="location.reload();" class="btn bg-danger mr-2"><i
                                        class="icon-sync"></i></button>
                                <button type="button" class="btn bg-success"><i class="icon-printer"></i></button>
                            </div>
                        </div>
                        {{-- <a href="{{url("admin/report/accounting/ledger")."?coa_id=".$rowchild->id."&start=".$date_start."&end=".$date_end."&branch=".$branch }}"></a> --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="mb-3">
            <h6 class="mb-0 font-weight-semibold text-center text-uppercase">
                <span id="string_filter_periode"></span>
            </h6>
        </div>
        <div class="card">
            <div class="card-header header-elements-inline">
                <h5 class="card-title">Results</h5>
            </div>
            <div class="card-body" id="cash_flow_card">
                <div class="col-md-12" id="result" style="/* height:400px;overflow:auto; */">
                    <div class="alert alert-warning alert-styled-left">
                        Please choose branch and date period.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Info modal -->
	<div id="modal_detail" class="modal fade" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content" style="max-width: 800px !important;">
				<div class="modal-header bg-info">
					<h6 class="modal-title">Purchase <b id="modal_title"></b></h6>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
					<h5 class="card-title"><b>Detail COA</b></h5>
					<div class="form-group"><hr></div>
					<div class="form-group">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-secondary">
                                    <tr class="text-center">
									   <th>No</th>
									   <th>Code</th>
                                       <th>Name</th>
                                       <th>Total</th>
                                    </tr>
                                 </thead>
                                 <tbody id="data_cashflow_detail">
                                    
                                 </tbody>
                              </table>
                        </div>
                    </div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
    <script>
    $(function() {
		$('.sidebar-main-toggle').click();

        // var counterConfig = {};
        // var dynamic_condition1 = ``;
        // var dynamic_condition2 = ``;
        // var dynamic_condition3 = ``;

        // @foreach (DB::table('company_entities')->get() as $company)
		// 	counterConfig['{{ $company->id }}'] = {
		// 		'1': { counter: 'debet{{$company->name}}', totalCounter: 'jumDebet' },
		// 		'2': { counter: 'kredit{{$company->name}}', totalCounter: 'jumKredit' }
		// 	};
   		//  @endforeach


        // $.each(counterConfig, function (companyId, counters) {
        //     dynamic_condition3 += `(${counters['1'].counter}` + ` > 0 && ${counters['2'].counter}` + ` > 0`;
        //     $.each(counterConfig, function (compIdLoopSecond, counterSecond) {
        //         if((companyId !== compIdLoopSecond) && (companyId === Object.keys(counterConfig).length.toString() && compIdLoopSecond === (Object.keys(counterConfig).length - 1).toString())){
		// 			dynamic_condition1 += `(${counters['1'].counter}` + ` == 1 && ${counterSecond['2'].counter}` + ` == 1)`;
		// 			dynamic_condition3 += ` && ${counterSecond['1'].counter}` + ` == 0 && ${counterSecond['2'].counter}` + ` == 0)`;
        //         }else if((companyId !== compIdLoopSecond) && compIdLoopSecond === Object.keys(counterConfig).length.toString()){
        //             dynamic_condition3 +=  ` && ${counterSecond['1'].counter}` + ` == 0 && ${counterSecond['2'].counter}` + ` == 0) || `;
        //         }else if(companyId !== compIdLoopSecond){
		// 			dynamic_condition1 += `(${counters['1'].counter}` + ` == 1  && ${counterSecond['2'].counter}` + ` == 1 ) || `;
		// 			dynamic_condition3 +=  ` && ${counterSecond['1'].counter}` + ` == 0 && ${counterSecond['2'].counter}` + ` == 0 `;
        //         }
        //     });
		// });

        // // console.log(dynamic_condition1);
        // // console.log(dynamic_condition2);
        // console.log(dynamic_condition3);

        

	});

	function generate(){
		$.ajax({
		 url: '{{ url("admin/report/accounting/cash_flow/get_cash_flow") }}',
		 type: 'GET',
		 dataType: 'JSON',
		 data: { 
            branch: $('#filter_branch').val(), 
            start_date: $('#filter_start_date').val(), 
            finish_date: $('#filter_finish_date').val() 
        },
		 headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		 },
		 beforeSend: function() {
			loadingOpen('.content-wrapper');
		 },
		 success: function(response) {
			loadingClose('.content-wrapper');
			if(response.status == 200) {
                $('#cash_flow_card').html('');
                $('#cash_flow_card').append(
                    `
                    <div class="row">
                        <div class="col-md-12" id="result">
                            <table class="table table-sm table-bordered fixed_header">
                                <thead>
                                    <tr class="text-center bg-primary font-weight-bold" style="font-size:18px;">
                                        <th>KETERANGAN</th>
                                        <th>SALDO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="font-size:15pt;"><b>Aktivitas Operasi</b></td>
                                        <td class="text-right">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-left:100px;font-size:12pt;"><b>Dari Laporan Laba/ Rugi</b></td>
                                        <td class="text-right">
                                             <a href="javascript:void(0);">`
                                                + response.data.totals_current_period.retained_earning.toLocaleString("id-ID") +
                                            `</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-left:100px;font-size:12pt;"><b>Tambah Piutang</b></td>
                                        <td class="text-right">
                                            <a href="javascript:void(0);" onclick="generateDetailCashFlow('increase_in_receivable')">
                                            `+ response.data.totals_current_period.increase_in_receivable.toLocaleString("id-ID") +`
                                            <a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-left:100px;font-size:12pt;"><b>Tambah Persediaan</b></td>
                                        <td class="text-right">
                                            <a href="javascript:void(0);" onclick="generateDetailCashFlow('increase_in_inventory')">
                                            `+ response.data.totals_current_period.increase_in_inventory.toLocaleString("id-ID") +`
                                            <a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-left:100px;font-size:12pt;"><b>Tambah Aktiva Lancar Lainnya</b>
                                        </td>
                                        <td class="text-right">
                                              <a href="javascript:void(0);" onclick="generateDetailCashFlow('increase_in_other_current_assets')">`
                                                + response.data.totals_current_period.increase_in_other_current_assets.toLocaleString("id-ID") +
                                            `<a>
                                            
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-left:100px;font-size:12pt;"><b>Tambah Akumulasi Penyusutan</b>
                                        </td>
                                        <td class="text-right">
                                              <a href="javascript:void(0);" onclick="generateDetailCashFlow('increase_in_accumulated_depreciation')">`
                                                + response.data.totals_current_period.increase_in_accumulated_depreciation.toLocaleString("id-ID") +
                                             `<a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-left:100px;font-size:12pt;"><b>Tambah Hutang</b></td>
                                        <td class="text-right">
                                            <a href="javascript:void(0);" onclick="generateDetailCashFlow('increase_in_payable')">
                                            `+ response.data.totals_current_period.increase_in_payable.toLocaleString("id-ID") +`
                                            <a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-left:100px;font-size:12pt;"><b>Tambah Hutang Lancar Lainnya</b>
                                        </td>
                                        <td class="text-right">
                                            <a href="javascript:void(0);" onclick="generateDetailCashFlow('increase_in_other_payable')">
                                            `+ response.data.totals_current_period.increase_in_other_payable.toLocaleString("id-ID") +`
                                            <a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="background-color:#cccccc; padding-left:100px;font-size:12pt;"><b>Kurang
                                                Piutang</b></td>
                                        <td class="text-right" style="background-color:#cccccc;">
                                            <a href="javascript:void(0);" onclick="generateDetailCashFlow('decrease_in_receivable')">
                                            `+ response.data.totals_current_period.decrease_in_receivable.toLocaleString("id-ID") +`
                                            <a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="background-color:#cccccc; padding-left:100px;font-size:12pt;"><b>Kurang
                                                Persediaan</b></td>
                                        <td class="text-right" style="background-color:#cccccc;">
                                            <a href="javascript:void(0);" onclick="generateDetailCashFlow('decrease_in_inventory')">
                                            `+ response.data.totals_current_period.decrease_in_inventory.toLocaleString("id-ID") +`
                                            <a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="background-color:#cccccc; padding-left:100px;font-size:12pt;"><b>Kurang
                                                Aktiva Lancar Lainnya</b></td>
                                        <td class="text-right" style="background-color:#cccccc;">
                                            <a href="javascript:void(0);" onclick="generateDetailCashFlow('decrease_in_other_current_assets')">`
                                                + response.data.totals_current_period.decrease_in_other_current_assets.toLocaleString("id-ID") +
                                            `<a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="background-color:#cccccc; padding-left:100px;font-size:12pt;"><b>Kurang
                                                Akumulasi Penyusutan</b></td>
                                        <td class="text-right" style="background-color:#cccccc;">
                                            <a href="javascript:void(0);" onclick="generateDetailCashFlow('decrease_in_accumulated_depreciation')">`
                                             + response.data.totals_current_period.decrease_in_accumulated_depreciation.toLocaleString("id-ID") +
                                            `<a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="background-color:#cccccc; padding-left:100px;font-size:12pt;"><b>Kurang
                                                Hutang</b></td>
                                        <td class="text-right" style="background-color:#cccccc;">
                                            <a href="javascript:void(0);" onclick="generateDetailCashFlow('decrease_in_payable')">
                                            `+ response.data.totals_current_period.decrease_in_payable.toLocaleString("id-ID") +`
                                            <a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="background-color:#cccccc; padding-left:100px;font-size:12pt;"><b>Kurang
                                                Hutang Lancar Lainnya</b></td>
                                        <td class="text-right" style="background-color:#cccccc;">
                                            <a href="javascript:void(0);" onclick="generateDetailCashFlow('decrease_in_other_payable')">
                                            `+ response.data.totals_current_period.decrease_in_other_payable.toLocaleString("id-ID") +`
                                            <a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="background-color:#cccccc; padding-left:100px;font-size:12pt;"><b>Kurang
                                                Laba Bersih</b></td>
                                        <td class="text-right" style="background-color:#cccccc;">
                                            <a href="javascript:void(0);" onclick="generateDetailCashFlow('decrease_in_net_income')">
                                            `+ response.data.totals_current_period.decrease_in_net_income.toLocaleString("id-ID") +`
                                            <a>
                                        </td>
                                    </tr>
                                    <tr class="bg-brown-300">
                                        <td style="font-size:15pt;"><b>Total Aktivitas Operasi</b></td>
                                        <td class="text-right">
                                            <b>`+response.data.total_operation_activities+`</b>
                                        </td>
                                    </tr>
                                    <tr class="font-weight-bold"
                                        style="font-size:15px;border-right: hidden !important;border-left: hidden !important;">
                                        <td colspan="2">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:15pt;"><b>Investasi</b></td>
                                        <td class="text-right">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-left:100px;font-size:12pt;"><b>Tambah Aktiva Tetap</b></td>
                                        <td class="text-right">
                                            <a href="javascript:void(0);" onclick="generateDetailCashFlow('increase_in_fixed_assets')">
                                            `+ response.data.totals_current_period.increase_in_fixed_assets.toLocaleString("id-ID") +`
                                            <a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-left:100px;font-size:12pt;"><b>Kurang Aktiva Tetap</b>
                                        </td>
                                        <td class="text-right">
                                            <a href="javascript:void(0);" onclick="generateDetailCashFlow('decrease_in_fixed_assets')">
                                            `+ response.data.totals_current_period.decrease_in_fixed_assets.toLocaleString("id-ID") +`
                                            <a>
                                        </td>
                                    </tr>
                                    <tr class="bg-brown-300">
                                        <td style="font-size:15pt;"><b>Total Investasi</b></td>
                                        <td class="text-right">
                                            <b>`+response.data.total_investment+`</b>
                                        </td>
                                    </tr>
                                    <tr class="font-weight-bold"
                                        style="font-size:15px;border-right: hidden !important;border-left: hidden !important;">
                                        <td colspan="2">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:15pt;"><b>Pendanaan</b></td>
                                        <td class="text-right">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-left:100px;font-size:12pt;"><b>Tambah Hutang Jangka Panjang</b>
                                        </td>
                                        <td class="text-right">
                                            <a href="javascript:void(0);" onclick="generateDetailCashFlow('increase_in_long_term_debt')">
                                            `+ response.data.totals_current_period.increase_in_long_term_debt.toLocaleString("id-ID") +`
                                            <a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-left:100px;font-size:12pt;"><b>Tambah Ekuitas</b>
                                        </td>
                                        <td class="text-right">
                                            <a href="javascript:void(0);" onclick="generateDetailCashFlow('increase_in_equity')">
                                            `+ response.data.totals_current_period.increase_in_equity.toLocaleString("id-ID") +`
                                            <a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-left:100px;font-size:12pt;"><b>Kurang Hutang Jangka Panjang</b>
                                        </td>
                                        <td class="text-right">
                                            <a href="javascript:void(0);" onclick="generateDetailCashFlow('decrease_in_long_term_debt')">
                                            `+ response.data.totals_current_period.decrease_in_long_term_debt.toLocaleString("id-ID") +`
                                            <a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-left:100px;font-size:12pt;"><b>Kurang Ekuitas</b>
                                        </td>
                                        <td class="text-right">
                                            <a href="javascript:void(0);" onclick="generateDetailCashFlow('decrease_in_equity')">
                                            `+ response.data.totals_current_period.decrease_in_equity.toLocaleString("id-ID") +`
                                            <a>
                                        </td>
                                    </tr>
                                    <tr class="bg-brown-300">
                                        <td style="font-size:15pt;"><b>Total Pendanaan</b></td>
                                        <td class="text-right">
                                            <b>`+response.data.total_funding+`</b>
                                        </td>
                                    </tr>
                                    <tr class="font-weight-bold"
                                        style="font-size:15px;border-right: hidden !important;border-left: hidden !important;">
                                        <td colspan="2">&nbsp;</td>
                                    </tr>
                                    <tr class="bg-green-300">
                                        <td style="font-size:15pt;"><b>Total Arus Kas Yang Digunakan Periode Ini</b></td>
                                        <td class="text-right">
                                            <b>`+response.data.current_period_cash+`</b>
                                        </td>
                                    </tr>
                                    <tr class="">
                                        <td style="font-size:15pt;"><b>Kas dan Setara Kas diawal Periode</b></td>
                                        <td class="text-right">
                                            <b>`+response.data.beginning_cash+`</b>
                                        </td>
                                    </tr>
                                    <tr class="">
                                        <td style="font-size:15pt;"><b>Kas dan Setara Kas diakhir Periode</b></td>
                                        <td class="text-right">
                                            <b>`+response.data.ending_cash+`</b>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    `
                );
			}
		 },
		 error: function() {
			loadingClose('.content-wrapper');
			swalInit.fire({
			   title: 'Server Error',
			   text: 'Please contact developer',
			   type: 'error'
			});
		 }
		});
	}

    function print(){
        
    }

    function generateDetailCashFlow(type){
    $.ajax({
         url: '{{ url("admin/report/accounting/cash_flow/get_detail_cash_flow") }}',
         type: 'GET',
		 dataType: 'JSON',
		 data: { 
            type: type, 
            branch: $('#filter_branch').val(), 
            start_date: $('#filter_start_date').val(), 
            finish_date: $('#filter_finish_date').val() 
        },
        beforeSend: function() {
            $('#modal_detail').modal('toggle');
            $('#modal_title').html(type.replace(/_/g, ' ').toUpperCase());
			loadingOpen('.modal-content');
		 },
         success: function(response) {
            loadingClose('.modal-content');
            var no = 1;
            var total = 0;
            $('#data_cashflow_detail').empty();
            $.each(response.coa_list, function (i, val) { 
                 $('#data_cashflow_detail').append(`
                 <tr class="">
                    <td class="text-right">
                        <b>`+no+`</b>
                    </td>
                    <td class="text-right">
                        <b>`+val.code+`</b>
                    </td>
                    <td class="text-right">
                        <b>`+val.name+`</b>
                    </td>
                    <td class="text-right">
                        <b>`+val.nominal.toLocaleString("id-ID")+`</b>
                    </td>
                 </tr>
                 `);
                 no++;
                 total += Number(val.nominal);
            });
            $('#data_cashflow_detail').append(
                `
                <tr>
                    <td colspan="2"></td>
                    <td class="text-center"><b>Total</b></td>
                    <td class="text-right"><b>`+ total.toLocaleString("id-ID") +`</b></td>
                </tr>
                `
            );
            
        
         }, 
         error: function() {
            loadingClose('.modal-content');
            swalInit.fire({
               title: 'Server Error',
               text: 'Please contact developer',
               type: 'error'
            });
         }
      });
    }
 

    </script>