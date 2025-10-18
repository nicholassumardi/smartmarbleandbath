<style>
	.card {
		border: 1px solid #818181;
	}
	.parent {
		background-color:#6b6b6b;
		color:white;
	}
	
	.child {
		background-color:#a1a1a1;
		color:white;
	}
</style>
<div class="content-wrapper">
    <div class="page-header page-header-light">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4>
                    <i class="icon-arrow-left52 mr-2"></i>
                    <span class="font-weight-semibold">Balance Sheet</span>
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
                    <span class="breadcrumb-item active">Balance Sheet</span>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="card">
            <div class="card-body">
                <h6 class="text-muted text-uppercase text-center font-weight-bold">
                    Periode {{ date('F Y', strtotime($filter)) }}</h6>
                <form method="GET" id="form_filter">
                    @csrf
                    <div class="form-group">
                        <center class="d-block">
                            <div class="row justify-content-center">
                                <div class="col-md-3">
                                    <input type="month" name="filter" id="filter" class="form-control" style="height:32px;" value="{{ $filter }}" onchange="submitFilter()">
                                </div>
                                @if (date('Y-m') != $filter)
                                    <div class="col-md-1">
                                        <a href="{{ url('admin/report/accounting/balance_sheet') }}"
                                            class="btn bg-danger btn-sm">Reset</a>
                                    </div>
                                @endif
                            </div>
                        </center>
                    </div>
                </form>
                <div class="form-group">
                    <hr>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-white">
                                <h6 class="card-title font-weight-bold text-uppercase">Current Assets</h6>
                            </div>
                            <div class="card-body">
                                <div class="row border p-1 parent">
                                    <div class="col-md-12 text-uppercase font-weight-bolder font-italic">Cash & Bank
                                    </div>
                                </div>
                                @foreach ($balance_sheet['assets']['cash_bank'] as $cb)
                                    <div class="row p-1 border child">
                                        <div class="col-md-9 font-weight-semibold">{{ $cb['name'] }}</div>
                                        <div class="col-md-3">
                                            {{ is_numeric($cb['balance']) ? number_format($cb['balance'], 2, ',', '.') : '' }}
                                        </div>
                                    </div>
                                    @if ($cb['sub'])
                                        @foreach ($cb['sub'] as $s)
                                            <div class="row p-1 border">
                                                <div class="col-md-9"><span
                                                        class="ml-4">{{ $s['name'] }}</span></div>
                                                <div class="col-md-3">
                                                    {{ number_format($s['balance'], 2, ',', '.') }}</div>
                                            </div>
                                        @endforeach
                                    @endif
                                @endforeach
                                <div class="row border p-1 parent">
                                    <div class="col-md-9 text-uppercase font-weight-bolder font-italic">Total Cash &
                                        Bank</div>
                                    <div class="col-md-3 text-right font-weight-bolder font-italic">
                                        {{ number_format($balance_sheet['assets']['total']['total_cash_bank'], 2, ',', '.') }}
                                    </div>
                                </div>
                                <div class="row border p-1 parent">
                                    <div class="col-md-12 text-uppercase font-weight-bolder font-italic">Account
                                        Receivable</div>
                                </div>
                                @foreach ($balance_sheet['assets']['receivable'] as $r)
                                    <div class="row p-1 border child">
                                        <div class="col-md-9 font-weight-semibold">{{ $r['name'] }}</div>
                                        <div class="col-md-3">
                                            {{ is_numeric($r['balance']) ? number_format($r['balance'], 2, ',', '.') : '' }}
                                        </div>
                                    </div>
                                    @if ($r['sub'])
                                        @foreach ($r['sub'] as $s)
                                            <div class="row p-1 border">
                                                <div class="col-md-9"><span
                                                        class="ml-4">{{ $s['name'] }}</span></div>
                                                <div class="col-md-3">
                                                    {{ number_format($s['balance'], 2, ',', '.') }}</div>
                                            </div>
                                        @endforeach
                                    @endif
                                @endforeach
                                <div class="row border p-1 parent">
                                    <div class="col-md-9 text-uppercase font-weight-bolder font-italic parent">Total Account
                                        Receivable</div>
                                    <div class="col-md-3 text-right font-weight-bolder font-italic">
                                        {{ number_format($balance_sheet['assets']['total']['total_receivable'], 2, ',', '.') }}
                                    </div>
                                </div>
                                <div class="row border p-1 parent">
                                    <div class="col-md-12 text-uppercase font-weight-bolder font-italic">Inventory</div>
                                </div>
                                @foreach ($balance_sheet['assets']['supply'] as $su)
                                    <div class="row p-1 border child">
                                        <div class="col-md-9 font-weight-semibold">{{ $su['name'] }}</div>
                                        <div class="col-md-3">
                                            {{ is_numeric($su['balance']) ? number_format($su['balance'], 2, ',', '.') : '' }}
                                        </div>
                                    </div>
                                    @if ($su['sub'])
                                        @foreach ($su['sub'] as $s)
                                            <div class="row p-1 border">
                                                <div class="col-md-9"><span
                                                        class="ml-4">{{ $s['name'] }}</span></div>
                                                <div class="col-md-3">
                                                    {{ number_format($s['balance'], 2, ',', '.') }}</div>
                                            </div>
                                        @endforeach
                                    @endif
                                @endforeach
                                <div class="row border p-1 parent">
                                    <div class="col-md-9 text-uppercase font-weight-bolder font-italic">Total Inventory
                                    </div>
                                    <div class="col-md-3 text-right font-weight-bolder font-italic">
                                        {{ number_format($balance_sheet['assets']['total']['total_supply'], 2, ',', '.') }}
                                    </div>
                                </div>
                                <div class="row border p-1 parent">
                                    <div class="col-md-12 text-uppercase font-weight-bolder font-italic">Other Current
                                        Assets</div>
                                </div>
                                @foreach ($balance_sheet['assets']['assets_facile'] as $af)
                                    <div class="row p-1 border child">
                                        <div class="col-md-9 font-weight-semibold">{{ $af['name'] }}</div>
                                        <div class="col-md-3">
                                            {{ is_numeric($af['balance']) ? number_format($af['balance'], 2, ',', '.') : '' }}
                                        </div>
                                    </div>
                                    @if ($af['sub'])
                                        @foreach ($af['sub'] as $s)
                                            <div class="row p-1 border">
                                                <div class="col-md-9"><span
                                                        class="ml-4">{{ $s['name'] }}</span></div>
                                                <div class="col-md-3">
                                                    {{ number_format($s['balance'], 2, ',', '.') }}</div>
                                            </div>
                                        @endforeach
                                    @endif
                                @endforeach
                                <div class="row border p-1 parent">
                                    <div class="col-md-9 text-uppercase font-weight-bolder font-italic">Total Other
                                        Current Assets</div>
                                    <div class="col-md-3 text-right font-weight-bolder font-italic">
                                        {{ number_format($balance_sheet['assets']['total']['total_assets_facile'], 2, ',', '.') }}
                                    </div>
                                </div>
                                <div class="row border p-1 parent">
                                    <div class="col-md-12 text-uppercase font-weight-bolder font-italic">Assets
                                        Consistenly</div>
                                </div>
                                @foreach ($balance_sheet['assets']['assets_consistenly'] as $ac)
                                    <div class="row p-1 border child">
                                        <div class="col-md-9 font-weight-semibold">{{ $ac['name'] }}</div>
                                        <div class="col-md-3">
                                            {{ is_numeric($ac['balance']) ? number_format($ac['balance'], 2, ',', '.') : '' }}
                                        </div>
                                    </div>
                                    @if ($ac['sub'])
                                        @foreach ($ac['sub'] as $s)
                                            <div class="row p-1 border">
                                                <div class="col-md-9"><span
                                                        class="ml-4">{{ $s['name'] }}</span></div>
                                                <div class="col-md-3">
                                                    {{ number_format($s['balance'], 2, ',', '.') }}</div>
                                            </div>
                                        @endforeach
                                    @endif
                                @endforeach
                                <div class="row border p-1 parent">
                                    <div class="col-md-9 text-uppercase font-weight-bolder font-italic">Total Assets
                                        Consistenly</div>
                                    <div class="col-md-3 text-right font-weight-bolder font-italic">
                                        {{ number_format($balance_sheet['assets']['total']['total_assets_consistenly'], 2, ',', '.') }}
                                    </div>
                                </div>
                                <div class="row border p-1 parent">
                                    <div class="col-md-12 text-uppercase font-weight-bolder font-italic">Accumulated
                                        Shrinkage</div>
                                </div>
                                @foreach ($balance_sheet['assets']['accumulated_shrinkage'] as $as)
                                    <div class="row p-1 border child">
                                        <div class="col-md-9 font-weight-semibold">{{ $as['name'] }}</div>
                                        <div class="col-md-3">
                                            {{ is_numeric($as['balance']) ? $as['balance'] : '' }}
                                        </div>
                                    </div>
                                    @if ($as['sub'])
                                        @foreach ($as['sub'] as $s)
                                            <div class="row p-1 border">
                                                <div class="col-md-9"><span
                                                        class="ml-4">{{ $s['name'] }}</span></div>
                                                <div class="col-md-3">
                                                    {{ number_format($s['balance'], 2, ',', '.') }}</div>
                                            </div>
                                        @endforeach
                                    @endif
                                @endforeach
                                <div class="row border p-1 parent">
                                    <div class="col-md-9 text-uppercase font-weight-bolder font-italic">Total
                                        Accumulated Shrinkage</div>
                                    <div class="col-md-3 text-right font-weight-bolder font-italic">
                                        {{ number_format($balance_sheet['assets']['total']['total_accumulated_shrinkage'], 2, ',', '.') }}
                                    </div>
                                </div>
                            </div>
							</div>
                    </div>
                    <div class="col-md-6">
                        <div class="card" style="height:2408px;">
                            <div class="card-header bg-white">
                                <h6 class="card-title font-weight-bold text-uppercase">Current Liabilities</h6>
                            </div>
                            <div class="card-body">
                                <div class="row border p-1 parent">
                                    <div class="col-md-12 text-uppercase font-weight-bolder font-italic">Account
                                        Payable</div>
                                </div>
                                @foreach ($balance_sheet['responbility_equity']['debt'] as $d)
                                    <div class="row p-1 border child">
                                        <div class="col-md-9 font-weight-semibold">{{ $d['name'] }}</div>
                                        <div class="col-md-3">
                                            {{ is_numeric($d['balance']) ? $d['balance'] : '' }}
                                        </div>
                                    </div>
                                    @if ($d['sub'])
                                        @foreach ($d['sub'] as $s)
                                            <div class="row p-1 border">
                                                <div class="col-md-9"><span
                                                        class="ml-4">{{ $s['name'] }}</span></div>
                                                <div class="col-md-3">
                                                    {{ number_format($s['balance'], 2, ',', '.') }}</div>
                                            </div>
                                        @endforeach
                                    @endif
                                @endforeach
                                <div class="row border p-1 parent">
                                    <div class="col-md-9 text-uppercase font-weight-bolder font-italic">Total Account
                                        Payable</div>
                                    <div class="col-md-3 text-right font-weight-bolder font-italic">
                                        {{ number_format($balance_sheet['responbility_equity']['total']['total_debt'], 2, ',', '.') }}
                                    </div>
                                </div>
                                <div class="row border p-1 parent">
                                    <div class="col-md-12 text-uppercase font-weight-bolder font-italic">Other Current
                                        Liabilities</div>
                                </div>
                                @foreach ($balance_sheet['responbility_equity']['responbility'] as $r)
                                    <div class="row p-1 border child">
                                        <div class="col-md-9 font-weight-semibold">{{ $r['name'] }}</div>
                                        <div class="col-md-3">
                                            {{ is_numeric($r['balance']) ? number_format($r['balance'], 2, ',', '.') : '' }}
                                        </div>
                                    </div>
                                    @if ($r['sub'])
                                        @foreach ($r['sub'] as $s)
                                            <div class="row p-1 border">
                                                <div class="col-md-9"><span
                                                        class="ml-4">{{ $s['name'] }}</span></div>
                                                <div class="col-md-3">
                                                    {{ number_format($s['balance'], 2, ',', '.') }}</div>
                                            </div>
                                        @endforeach
                                    @endif
                                @endforeach
                                <div class="row border p-1 parent">
                                    <div class="col-md-9 text-uppercase font-weight-bolder font-italic">Total Other
                                        Current Liabilities</div>
                                    <div class="col-md-3 text-right font-weight-bolder font-italic">
                                        {{ number_format($balance_sheet['responbility_equity']['total']['total_responbility'], 2, ',', '.') }}
                                    </div>
                                </div>
                                <div class="row border p-1 parent">
                                    <div class="col-md-12 text-uppercase font-weight-bolder font-italic">Equity</div>
                                </div>
                                @foreach ($balance_sheet['responbility_equity']['equity'] as $e)
                                    <div class="row p-1 border child">
                                        <div class="col-md-9 font-weight-semibold">{{ $e['name'] }}</div>
                                        <div class="col-md-3">
                                            {{ is_numeric($e['balance']) ? number_format($e['balance'], 2, ',', '.') : '' }}
                                        </div>
                                    </div>
                                    @if ($e['sub'])
                                        @foreach ($e['sub'] as $s)
                                            <div class="row p-1 border">
                                                <div class="col-md-9"><span
                                                        class="ml-4">{{ $s['name'] }}</span></div>
                                                <div class="col-md-3">
                                                    {{ number_format($s['balance'], 2, ',', '.') }}</div>
                                            </div>
                                        @endforeach
                                    @endif
                                @endforeach
                                <div class="row border p-1 parent">
                                    <div class="col-md-9 text-uppercase font-weight-bolder font-italic">Total Equity
                                    </div>
                                    <div class="col-md-3 text-right font-weight-bolder font-italic">
                                        {{ number_format($balance_sheet['responbility_equity']['total']['total_equity'], 2, ',', '.') }}
                                    </div>
                                </div>
                            </div>
							 </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row p-2">
                            <div class="col-md-6">
                                <div class="row p-1 border border">
                                    <div class="col-md-8 text-uppercase font-weight-bold">Total Current Assets</div>
                                    <div class="col-md-4 font-weight-bold">
                                        {{ number_format(collect($balance_sheet['assets']['total'])->flatten()->sum(), 2, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row p-1 border border">
                                    <div class="col-md-8 text-uppercase font-weight-bold">Total Current Liabilities
                                    </div>
                                    <div class="col-md-4 font-weight-bold">
                                        {{ number_format(collect($balance_sheet['assets']['total'])->flatten()->sum(), 2, ',', '.') }}
                                    </div>
                                </div>
                            </div>
							</div>
                    </div>
                </div>	
				</div>
        </div>
    </div>
	<script>
        function submitFilter() {
            loadingOpen('.content');
            $('#form_filter').submit();
        }
    </script>