<div class="page-content">
    <div class="sidebar sidebar-dark sidebar-main sidebar-expand-md no-print">
        <div class="sidebar-mobile-toggler text-center">
            <a href="#" class="sidebar-mobile-main-toggle">
                <i class="icon-arrow-left8"></i>
            </a>
            Navigation
            <a href="#" class="sidebar-mobile-expand">
                <i class="icon-screen-full"></i>
                <i class="icon-screen-normal"></i>
            </a>
        </div>
        <div class="sidebar-content" style="position:sticky;top:75px;">
            <div class="card card-sidebar-mobile" style="height:90vh !important;overflow-y:auto !important;">
                <ul class="nav nav-sidebar" data-nav-type="accordion">
                    <li class="nav-item-header text-white font-weight-bold">
                        <div class="text-uppercase mb-0 font-weight-bold text-center">
                            <span style="font-size:35px;" id="header-clock-realtime">{{ date('H:i:s') }}</span>
                            <h5>{{ date('D, d M Y') }}</h5>
                        </div>
                    </li>
                    <li class="nav-item-header">
                        <div class="text-uppercase font-size-xs line-height-xs">Main Navigation</div>
                    </li>
                    @if(Request::segment(1) != 'cogs_calculator')
                    <li class="nav-item">
                        <a href="{{ url('admin/dashboard') }}" class="nav-link {{ Request::segment(2) == 'dashboard' ? 'active' : '' }}">
                            <i class="icon-home"></i>
                            <span>Dashboard</span>
							<span class="badge badge-warning badge-pill" style="position:absolute;top:0;right:0;"><i class="icon-construction" style="margin-right:0;"></i></span>
                        </a>
                    </li>
					@if(session('bo_branch') == '1')
					<li class="nav-item nav-item-submenu {{ Request::segment(2) == 'al' ? 'nav-item-expanded nav-item-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="icon-ship"></i>
                            <span>AL</span>
                        </a>
                        <ul class="nav nav-group-sub bg-primary" data-submenu-title="AL" style="z-index:10000 !important;">
							<li class="nav-item nav-item-submenu {{ Request::segment(2) == 'al' && Request::segment(3) == 'master_data' ? 'nav-item-expanded nav-item-open' : '' }}">
                                <a href="#" class="nav-link">Master Data</a>
                                <ul class="nav nav-group-sub">
                                    <li class="nav-item">
                                        <a href="{{ url('admin/al/master_data/customer') }}" class="nav-link {{ Request::segment(2) == 'al' && Request::segment(3) == 'master_data' && Request::segment(4) == 'customer' ? 'active' : '' }}">Customer</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin/al/master_data/supplier') }}" class="nav-link {{ Request::segment(2) == 'al' && Request::segment(3) == 'master_data' && Request::segment(4) == 'supplier' ? 'active' : '' }}">Supplier</a>
                                    </li>
									<li class="nav-item">
                                        <a href="{{ url('admin/al/master_data/barang') }}" class="nav-link {{ Request::segment(2) == 'al' && Request::segment(3) == 'master_data' && Request::segment(4) == 'barang' ? 'active' : '' }}">Barang</a>
                                    </li>
									<li class="nav-item">
                                        <a href="{{ url('admin/al/master_data/checklist') }}" class="nav-link {{ Request::segment(2) == 'al' && Request::segment(3) == 'master_data' && Request::segment(4) == 'checklist' ? 'active' : '' }}">Checklist</a>
                                    </li>
									<li class="nav-item">
                                        <a href="{{ url('admin/al/master_data/dokumen') }}" class="nav-link {{ Request::segment(2) == 'al' && Request::segment(3) == 'master_data' && Request::segment(4) == 'dokumen' ? 'active' : '' }}">Dokumen Tetap</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('admin/al/sph_dkh') }}" class="nav-link {{ Request::segment(2) == 'al' && Request::segment(3) == 'sph_dkh' ? 'active' : '' }}">Proyek, SPH & DKH</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('admin/al/rab') }}" class="nav-link {{ Request::segment(2) == 'al' && Request::segment(3) == 'rab' ? 'active' : '' }}">RAB</a>
                            </li>
							<li class="nav-item">
                                <a href="{{ url('admin/al/kelengkapan_data') }}" class="nav-link {{ Request::segment(2) == 'al' && Request::segment(3) == 'kelengkapan_data' ? 'active' : '' }}">Kelengkapan Data</a>
                            </li>
							<li class="nav-item">
                                <a href="{{ url('admin/al/surat_permohonan') }}" class="nav-link {{ Request::segment(2) == 'al' && Request::segment(3) == 'surat_permohonan' ? 'active' : '' }}">Surat Permohonan</a>
                            </li>
							<li class="nav-item">
                                <a href="{{ url('admin/al/po') }}" class="nav-link {{ Request::segment(2) == 'al' && Request::segment(3) == 'po' ? 'active' : '' }}">PO</a>
                            </li>
							<li class="nav-item">
                                <a href="{{ url('admin/al/surat_jalan') }}" class="nav-link {{ Request::segment(2) == 'al' && Request::segment(3) == 'surat_jalan' ? 'active' : '' }}">Surat Jalan</a>
                            </li>
							<li class="nav-item">
                                <a href="{{ url('admin/al/faktur_barang') }}" class="nav-link {{ Request::segment(2) == 'al' && Request::segment(3) == 'faktur_barang' ? 'active' : '' }}">Faktur Barang</a>
                            </li>
							<li class="nav-item nav-item-submenu {{ Request::segment(2) == 'al' && Request::segment(3) == 'finance' ? 'nav-item-expanded nav-item-open' : '' }}">
                                <a href="#" class="nav-link">Finance</a>
                                <ul class="nav nav-group-sub">
									<li class="nav-item">
                                        <a href="{{ url('admin/al/finance/pemasukan') }}" class="nav-link {{ Request::segment(2) == 'al' && Request::segment(3) == 'finance' && Request::segment(4) == 'pemasukan' ? 'active' : '' }}">Pemasukan</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin/al/finance/pengeluaran') }}" class="nav-link {{ Request::segment(2) == 'al' && Request::segment(3) == 'finance' && Request::segment(4) == 'pengeluaran' ? 'active' : '' }}">Pengeluaran</a>
                                    </li>
                                </ul>
                            </li>
							<li class="nav-item nav-item-submenu {{ Request::segment(2) == 'al' && Request::segment(3) == 'arsip' ? 'nav-item-expanded nav-item-open' : '' }}">
                                <a href="#" class="nav-link">Arsip</a>
                                <ul class="nav nav-group-sub">
                                    <li class="nav-item">
                                        <a href="{{ url('admin/al/arsip/supplier') }}" class="nav-link {{ Request::segment(2) == 'al' && Request::segment(3) == 'arsip' && Request::segment(4) == 'supplier' ? 'active' : '' }}">Supplier</a>
                                    </li>
									<li class="nav-item">
                                        <a href="{{ url('admin/al/arsip/spk_tni') }}" class="nav-link {{ Request::segment(2) == 'al' && Request::segment(3) == 'arsip' && Request::segment(4) == 'spk_tni' ? 'active' : '' }}">SPK TNI</a>
                                    </li>
                                </ul>
                            </li>
							<li class="nav-item">
                                <a href="{{ url('admin/al/checklist_proyek') }}" class="nav-link {{ Request::segment(2) == 'al' && Request::segment(3) == 'checklist_proyek' ? 'active' : '' }}">Checklist Proyek</a>
                            </li>
							<li class="nav-item nav-item-submenu {{ Request::segment(2) == 'al' && Request::segment(3) == 'report' ? 'nav-item-expanded nav-item-open' : '' }}">
                                <a href="#" class="nav-link">Laporan</a>
                                <ul class="nav nav-group-sub">
                                    <li class="nav-item">
                                        <a href="{{ url('admin/al/report/accounting') }}" class="nav-link {{ Request::segment(2) == 'al' && Request::segment(3) == 'report' && Request::segment(4) == 'accounting' ? 'active' : '' }}">Accounting</a>
                                    </li>
									<li class="nav-item">
                                        <a href="{{ url('admin/al/report/proyek') }}" class="nav-link {{ Request::segment(2) == 'al' && Request::segment(3) == 'report' && Request::segment(4) == 'proyek' ? 'active' : '' }}">Proyek</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
					@endif
                    <li class="nav-item nav-item-submenu {{ Request::segment(2) == 'master_data' ? 'nav-item-expanded nav-item-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="icon-archive"></i>
                            <span>Master Data</span>
                        </a>
                        <ul class="nav nav-group-sub" data-submenu-title="Master Data">
                            <li class="nav-item nav-item-submenu {{ Request::segment(2) == 'master_data' && Request::segment(3) == 'product' ? 'nav-item-expanded nav-item-open' : '' }}">
                                <a href="#" class="nav-link">Product</a>
                                <ul class="nav nav-group-sub">
									@if(session('bo_branch') == '1')
									<li class="nav-item">
                                        <a href="{{ url('admin/master_data/product/city_currency_warehouse') }}" class="nav-link {{ Request::segment(2) == 'master_data' && Request::segment(3) == 'product' && (Request::segment(4) == 'city' || Request::segment(4) == 'currency' || Request::segment(4) == 'warehouse' || Request::segment(4) == 'city_currency_warehouse') ? 'active' : '' }}">City, Currency & Warehouse</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin/master_data/product/product_type') }}" class="nav-link {{ Request::segment(2) == 'master_data' && Request::segment(3) == 'product' && Request::segment(4) == 'product_type' ? 'active' : '' }}">Product Type</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin/master_data/product/product_code') }}" class="nav-link {{ Request::segment(2) == 'master_data' && Request::segment(3) == 'product' && Request::segment(4) == 'product_code' ? 'active' : '' }}">SMB Item Code</a>
                                    </li>
									@endif
                                </ul>
                            </li>
							@if(session('bo_branch') == '1')
                            <li class="nav-item nav-item-submenu {{ Request::segment(2) == 'master_data' && Request::segment(3) == 'cogs_master' ? 'nav-item-expanded nav-item-open' : '' }}">
                                <a href="#" class="nav-link">COGS Master</a>
                                <ul class="nav nav-group-sub">
                                    <li class="nav-item">
                                        <a href="{{ url('admin/master_data/cogs_master/purchase_price') }}" class="nav-link {{ Request::segment(2) == 'master_data' && Request::segment(3) == 'cogs_master' && Request::segment(4) == 'purchase_price' ? 'active' : '' }}">Purchase Price</a>
                                    </li>
									<li class="nav-item">
                                        <a href="{{ url('admin/master_data/cogs_master/landed_cost') }}" class="nav-link {{ Request::segment(2) == 'master_data' && Request::segment(3) == 'cogs_master' && Request::segment(4) == 'landed_cost' ? 'active' : '' }}">Landed Cost</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin/master_data/cogs_master/marketing_cost') }}" class="nav-link {{ Request::segment(2) == 'master_data' && Request::segment(3) == 'cogs_master' && Request::segment(4) == 'marketing_cost' ? 'active' : '' }}">Marketing Cost</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin/master_data/cogs_master/cogs_sales') }}" class="nav-link {{ Request::segment(2) == 'master_data' && Request::segment(3) == 'cogs_master' && Request::segment(4) == 'cogs_sales' ? 'active' : '' }}">COGS Sales</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin/master_data/cogs_master/pricing_sales') }}" class="nav-link {{ Request::segment(2) == 'master_data' && Request::segment(3) == 'cogs_master' && Request::segment(4) == 'pricing_sales' ? 'active' : '' }}">Pricing Sales</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item nav-item-submenu {{ Request::segment(2) == 'master_data' && Request::segment(3) == 'delivery' ? 'nav-item-expanded nav-item-open' : '' }}">
                                <a href="#" class="nav-link">Delivery</a>
                                <ul class="nav nav-group-sub">
									<li class="nav-item">
                                        <a href="{{ url('admin/master_data/delivery/dropshipper') }}" class="nav-link {{ Request::segment(2) == 'master_data' && Request::segment(3) == 'delivery' && Request::segment(4) == 'dropshipper' ? 'active' : '' }}">Dropshipper</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin/master_data/delivery/delivery_company') }}" class="nav-link {{ Request::segment(2) == 'master_data' && Request::segment(3) == 'delivery' && Request::segment(4) == 'delivery_company' ? 'active' : '' }}">Delivery Company</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin/master_data/delivery/mode_of_transport') }}" class="nav-link {{ Request::segment(2) == 'master_data' && Request::segment(3) == 'delivery' && Request::segment(4) == 'mode_of_transport' ? 'active' : '' }}">Mode Of Transport</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin/master_data/delivery/delivery_cost') }}" class="nav-link {{ Request::segment(2) == 'master_data' && Request::segment(3) == 'delivery' && Request::segment(4) == 'delivery_cost' ? 'active' : '' }}">Delivery Cost</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item nav-item-submenu {{ Request::segment(2) == 'master_data' && Request::segment(3) == 'finance_accounting' ? 'nav-item-expanded nav-item-open' : '' }}">
                                <a href="#" class="nav-link">Finance & Accounting</a>
                                <ul class="nav nav-group-sub">
                                    <li class="nav-item">
                                        <a href="{{ url('admin/master_data/finance_accounting/coa') }}" class="nav-link {{ Request::segment(2) == 'master_data' && Request::segment(3) == 'finance_accounting' && Request::segment(4) == 'coa' ? 'active' : '' }}">COA</a>
                                    </li>
                                </ul>
                            </li>
							<li class="nav-item nav-item-submenu {{ Request::segment(2) == 'master_data' && Request::segment(3) == 'hrd' ? 'nav-item-expanded nav-item-open' : '' }}">
                                <a href="#" class="nav-link">HRD</a>
                                <ul class="nav nav-group-sub">
                                    <li class="nav-item">
                                        <a href="{{ url('admin/master_data/hrd/time_management') }}" class="nav-link {{ Request::segment(2) == 'master_data' && Request::segment(3) == 'hrd' && Request::segment(4) == 'time_management' ? 'active' : '' }}">Time Management & Company</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin/master_data/hrd/asset') }}" class="nav-link {{ Request::segment(2) == 'master_data' && Request::segment(3) == 'hrd' && Request::segment(4) == 'asset' ? 'active' : '' }}">Asset</a>
                                    </li>
									<li class="nav-item">
                                        <a href="{{ url('admin/master_data/hrd/allowance') }}" class="nav-link {{ Request::segment(2) == 'master_data' && Request::segment(3) == 'hrd' && Request::segment(4) == 'allowance' ? 'active' : '' }}">Allowance</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item nav-item-submenu {{ Request::segment(2) == 'master_data' && Request::segment(3) == 'digital' ? 'nav-item-expanded nav-item-open' : '' }}">
                                <a href="#" class="nav-link">Digital</a>
                                <ul class="nav nav-group-sub">
                                    <li class="nav-item">
                                        <a href="{{ url('admin/master_data/digital/banner') }}" class="nav-link {{ Request::segment(2) == 'master_data' && Request::segment(3) == 'digital' && Request::segment(4) == 'banner' ? 'active' : '' }}">Banner</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin/master_data/digital/career') }}" class="nav-link {{ Request::segment(2) == 'master_data' && Request::segment(3) == 'digital' && Request::segment(4) == 'career' ? 'active' : '' }}">Career</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin/master_data/digital/news_category') }}" class="nav-link {{ Request::segment(2) == 'master_data' && Request::segment(3) == 'digital' && Request::segment(4) == 'news_category' ? 'active' : '' }}">News Category</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin/master_data/digital/news') }}" class="nav-link {{ Request::segment(2) == 'master_data' && Request::segment(3) == 'digital' && Request::segment(4) == 'news' ? 'active' : '' }}">News</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item nav-item-submenu {{ Request::segment(2) == 'master_data' && Request::segment(3) == 'voucher' ? 'nav-item-expanded nav-item-open' : '' }}">
                                <a href="#" class="nav-link">Voucher</a>
                                <ul class="nav nav-group-sub">
                                    <li class="nav-item">
                                        <a href="{{ url('admin/master_data/voucher/brand') }}" class="nav-link {{ Request::segment(2) == 'master_data' && Request::segment(3) == 'voucher' && Request::segment(4) == 'brand' ? 'active' : '' }}">Brand</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin/master_data/voucher/category') }}" class="nav-link {{ Request::segment(2) == 'master_data' && Request::segment(3) == 'voucher' && Request::segment(4) == 'category' ? 'active' : '' }}">Category</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin/master_data/voucher/global') }}" class="nav-link {{ Request::segment(2) == 'master_data' && Request::segment(3) == 'voucher' && Request::segment(4) == 'global' ? 'active' : '' }}">Global</a>
                                    </li>
                                </ul>
                            </li>
							@endif
                            <li class="nav-item">
                                <a href="{{ url('admin/master_data/customer') }}" class="nav-link {{ Request::segment(2) == 'master_data' && Request::segment(3) == 'customer' ? 'active' : '' }}">Customer List</a>
                            </li>
                        </ul>
                    </li>
					<li class="nav-item nav-item-submenu {{ Request::segment(2) == 'inventory' ? 'nav-item-expanded nav-item-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="icon-cabinet"></i>
                            <span>Inventory</span>
                        </a>
                        <ul class="nav nav-group-sub" data-submenu-title="Inventory">
							@if(session('bo_branch') == '1')
                            <li class="nav-item">
                                <a href="{{ url('admin/inventory/purchase') }}" class="nav-link {{ Request::segment(2) == 'inventory' && Request::segment(3) == 'purchase' ? 'active' : '' }}">Purchase For Stock</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('admin/inventory/transfer') }}" class="nav-link {{ Request::segment(2) == 'inventory' && Request::segment(3) == 'transfer' ? 'active' : '' }}">Warehouse Exchange</a>
                            </li>
							@endif
							<li class="nav-item">
								<a href="{{ url('admin/inventory/internal_memo') }}" class="nav-link {{ Request::segment(2) == 'inventory' && Request::segment(3) == 'internal_memo' ? 'active' : '' }}">Internal Memo</a>
							</li>
							<li class="nav-item">
								<a href="{{ url('admin/inventory/ventura') }}" class="nav-link {{ Request::segment(2) == 'inventory' && Request::segment(3) == 'ventura' ? 'active' : '' }}">Ventura</a>
							</li>
							<li class="nav-item">
                                <a href="{{ url('admin/inventory/stock') }}" class="nav-link {{ Request::segment(2) == 'inventory' && Request::segment(3) == 'stock' ? 'active' : '' }}">SMB Stock</a>
                            </li>
                        </ul>
                    </li>
					<li class="nav-item nav-item-submenu {{ Request::segment(2) == 'sales' ? 'nav-item-expanded nav-item-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="icon-store2"></i>
                            <span>Sales</span>
                        </a>
                        <ul class="nav nav-group-sub" data-submenu-title="Sales">
							@if(session('bo_branch') == '1')
							<li class="nav-item">
								<a href="{{ url('admin/sales/budgeting_project') }}" class="nav-link {{ Request::segment(2) == 'sales' && Request::segment(3) == 'budgeting_project' ? 'active' : '' }}">Budgeting Project</a>
							</li>
							@endif
							<li class="nav-item">
								<a href="{{ url('admin/sales/project') }}" class="nav-link {{ Request::segment(2) == 'sales' && Request::segment(3) == 'project' ? 'active' : '' }}">Project</a>
							</li>
							@if(session('bo_branch') == '1')
                            <li class="nav-item">
								<a href="{{ url('admin/sales/sample') }}" class="nav-link {{ Request::segment(2) == 'sales' && Request::segment(3) == 'sample' ? 'active' : '' }}">Sample</a>
							</li>
							<li class="nav-item">
								<a href="{{ url('admin/sales/service_cost') }}" class="nav-link {{ Request::segment(2) == 'sales' && Request::segment(3) == 'service_cost' ? 'active' : '' }}">Service Charge</a>
							</li>
							<li class="nav-item">
								<a href="{{ url('admin/sales/in_store') }}" class="nav-link {{ Request::segment(2) == 'sales' && Request::segment(3) == 'in_store' ? 'active' : '' }}">Retail Store</a>
							</li>
							<li class="nav-item">
								<a href="{{ url('admin/sales/retail') }}" class="nav-link {{ Request::segment(2) == 'sales' && Request::segment(3) == 'retail' ? 'active' : '' }}">Online Retail</a>
							</li>
                            {{-- <li class="nav-item">
								<a href="{{ url('admin/sales/field_trip') }}" class="nav-link {{ Request::segment(2) == 'sales' && Request::segment(3) == 'field_trip' ? 'active' : '' }}">Field Trip</a>
							</li> --}}
							@endif
						</ul>
					</li>
					@if(session('bo_branch') == '1')
					<li class="nav-item nav-item-submenu {{ Request::segment(2) == 'invoice' ? 'nav-item-expanded nav-item-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="icon-profile"></i>
                            <span>Invoice</span>
                        </a>
                        <ul class="nav nav-group-sub" data-submenu-title="Invoice">
							<li class="nav-item">
								<a href="{{ url('admin/invoice/retail') }}" class="nav-link {{ Request::segment(2) == 'invoice' && Request::segment(3) == 'retail' ? 'active' : '' }}">Online Sales</a>
							</li>
						</ul>
					</li>
					
					<li class="nav-item nav-item-submenu {{ Request::segment(2) == 'purchase_order' ? 'nav-item-expanded nav-item-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="icon-cube4"></i>
                            <span>Purchase</span>
                        </a>
                        <ul class="nav nav-group-sub" data-submenu-title="Purchase Order">
							<li class="nav-item">
								<a href="{{ url('admin/purchase_order/project') }}" class="nav-link {{ Request::segment(2) == 'purchase_order' && Request::segment(3) == 'project' ? 'active' : '' }}">Project</a>
							</li>
							<li class="nav-item">
								<a href="{{ url('admin/purchase_order/retail') }}" class="nav-link {{ Request::segment(2) == 'purchase_order' && Request::segment(3) == 'retail' ? 'active' : '' }}">Online Retail</a>
							</li>
						</ul>
					</li>
					
					<li class="nav-item nav-item-submenu {{ Request::segment(2) == 'delivery_order' ? 'nav-item-expanded nav-item-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="icon-truck"></i>
                            <span>Delivery</span>
                        </a>
                        <ul class="nav nav-group-sub" data-submenu-title="Delivery Order">
							<li class="nav-item">
								<a href="{{ url('admin/delivery_order/project') }}" class="nav-link {{ Request::segment(2) == 'delivery_order' && Request::segment(3) == 'project' ? 'active' : '' }}">Project</a>
							</li>
                            <li class="nav-item nav-item-submenu {{ Request::segment(2) == 'delivery_order' && (Request::segment(3) == 'project_payment' || Request::segment(3) == 'receivable_payment') ? 'nav-item-expanded nav-item-open' : '' }}">
                                <a href="#" class="nav-link">Payment</a>
                                <ul class="nav nav-group-sub">
                                    <li class="nav-item">
                                        <a href="{{ url('admin/delivery_order/project_payment') }}" class="nav-link {{ Request::segment(2) == 'delivery_order' && Request::segment(3) == 'project_payment' ? 'active' : '' }}">Project Payment</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin/delivery_order/receivable_payment') }}" class="nav-link {{ Request::segment(2) == 'delivery_order' && Request::segment(3) == 'receivable_payment' ? 'active' : '' }}">Other Payment</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin/delivery_order/recap_payment') }}" class="nav-link {{ Request::segment(2) == 'delivery_order' && Request::segment(3) == 'recap_payment' ? 'active' : '' }}">Recap Payment</a>
                                    </li>
                                </ul>
                            </li>
							<li class="nav-item">
								<a href="{{ url('admin/delivery_order/delivery_status') }}" class="nav-link {{ Request::segment(2) == 'delivery_order' && Request::segment(3) == 'delivery_status' ? 'active' : '' }}">Delivery Status</a>
							</li>
							<li class="nav-item">
								<a href="{{ url('admin/delivery_order/retail') }}" class="nav-link {{ Request::segment(2) == 'delivery_order' && Request::segment(3) == 'retail' ? 'active' : '' }}">Online Retail</a>
							</li>
						</ul>
					</li>
					
                    @endif
					@if(session('bo_branch') == '1')
					<li class="nav-item nav-item-submenu {{ Request::segment(2) == 'finance' ? 'nav-item-expanded nav-item-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="icon-cash4"></i>
                            <span>Finance</span>
                        </a>
						<ul class="nav nav-group-sub" data-submenu-title="Finance">
							<li class="nav-item">
								<a href="{{ url('admin/finance/cash_flow') }}" class="nav-link {{ Request::segment(2) == 'finance' && Request::segment(3) == 'cash_flow' ? 'active' : '' }}">Budgeting Cash Flow</a>
							</li>
							<li class="nav-item">
								<a href="{{ url('admin/finance/balance_cash_bank') }}" class="nav-link {{ Request::segment(2) == 'finance' && Request::segment(3) == 'balance_cash_bank' ? 'active' : '' }}">Balance Cash & Bank<span class="badge badge-success badge-pill" style="position:absolute;top:0;right:0;"><i class="icon-construction" style="margin-right:0;"></i></span></a>
							</li>
							<li class="nav-item">
								<a href="{{ url('admin/finance/purchase_request') }}" class="nav-link {{ Request::segment(2) == 'finance' && Request::segment(3) == 'purchase_request' ? 'active' : '' }}">Purchase Request</a>
							</li>
							<li class="nav-item">
								<a href="{{ url('admin/finance/payment_purchase') }}" class="nav-link {{ Request::segment(2) == 'finance' && Request::segment(3) == 'payment_purchase' ? 'active' : '' }}">Payment Purchase</a>
							</li>
							<li class="nav-item">
								<a href="{{ url('admin/finance/receivable_payment') }}" class="nav-link {{ Request::segment(2) == 'finance' && Request::segment(3) == 'receivable_payment' ? 'active' : '' }}">Other Payment</a>
							</li>
							<li class="nav-item">
								<a href="{{ url('admin/finance/payment_request') }}" class="nav-link {{ Request::segment(2) == 'finance' && Request::segment(3) == 'payment_request' ? 'active' : '' }}">Payment Request</a>
							</li>
							<li class="nav-item">
								<a href="{{ url('admin/finance/cash_bank') }}" class="nav-link {{ Request::segment(2) == 'finance' && Request::segment(3) == 'cash_bank' ? 'active' : '' }}">Cash & Bank</a>
							</li>
						</ul>
					</li>
					
					<li class="nav-item nav-item-submenu {{ Request::segment(2) == 'accounting' ? 'nav-item-expanded nav-item-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="icon-percent"></i>
                            <span>Accounting</span>
                        </a>
						<ul class="nav nav-group-sub" data-submenu-title="Accounting">
							<li class="nav-item">
								<a href="{{ url('admin/accounting/budgeting') }}" class="nav-link {{ Request::segment(2) == 'accounting' && Request::segment(3) == 'budgeting' ? 'active' : '' }}">Cost Budgeting</a>
							</li>
						</ul>
					</li>
                    @endif
					@if(session('bo_branch') == '1')
                    <li class="nav-item nav-item-submenu {{ Request::segment(2) == 'report' ? 'nav-item-expanded nav-item-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="icon-file-text3"></i>
                            <span>Report</span>
                        </a>
                        <ul class="nav nav-group-sub" data-submenu-title="Report">
							<li class="nav-item nav-item-submenu {{ Request::segment(2) == 'report' && Request::segment(3) == 'project' ? 'nav-item-expanded nav-item-open' : '' }}">
                                <a href="#" class="nav-link">Project</a>
                                <ul class="nav nav-group-sub">
									<li class="nav-item">
										<a href="{{ url('admin/report/project/details') }}" class="nav-link {{ Request::segment(2) == 'report' && Request::segment(3) == 'project' && Request::segment(4) == 'details' ? 'active' : '' }}">Details</a>
									</li>
									<li class="nav-item">
										<a href="{{ url('admin/report/project/summary') }}" class="nav-link {{ Request::segment(2) == 'report' && Request::segment(3) == 'project' && Request::segment(4) == 'summary' ? 'active' : '' }}">Summary</a>
									</li>
									<li class="nav-item">
										<a href="{{ url('admin/report/project/sales_chart') }}" class="nav-link {{ Request::segment(2) == 'report' && Request::segment(3) == 'project' && Request::segment(4) == 'sales_chart' ? 'active' : '' }}">Sales Chart</a>
									</li>
									<li class="nav-item">
										<a href="{{ url('admin/report/project/files') }}" class="nav-link {{ Request::segment(2) == 'report' && Request::segment(3) == 'project' && Request::segment(4) == 'files' ? 'active' : '' }}">Files</a>
									</li>
                                </ul>
                            </li>
							<li class="nav-item nav-item-submenu {{ Request::segment(2) == 'report' && Request::segment(3) == 'inventory' ? 'nav-item-expanded nav-item-open' : '' }}">
                                <a href="#" class="nav-link">Inventory</a>
                                <ul class="nav nav-group-sub">
									<li class="nav-item">
                                        <a href="{{ url('admin/report/inventory/product_inventory') }}" class="nav-link {{ Request::segment(2) == 'report' && Request::segment(3) == 'inventory' && Request::segment(4) == 'product_inventory' ? 'active' : '' }}">Product (in Rp)</a>
                                    </li>
									<li class="nav-item">
                                        <a href="{{ url('admin/report/inventory/stock_card') }}" class="nav-link {{ Request::segment(2) == 'report' && Request::segment(3) == 'inventory' && Request::segment(4) == 'stock_card' ? 'active' : '' }}">Stock Card</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item nav-item-submenu {{ Request::segment(2) == 'report' && Request::segment(3) == 'purchase_order' ? 'nav-item-expanded nav-item-open' : '' }}">
                                <a href="#" class="nav-link">Purchase Order</a>
                                <ul class="nav nav-group-sub">
									<li class="nav-item">
                                        <a href="{{ url('admin/report/purchase_order/warehouse_receive') }}" class="nav-link {{ Request::segment(2) == 'report' && Request::segment(3) == 'purchase_order' && Request::segment(4) == 'warehouse_receive' ? 'active' : '' }}">Warehouse Receive (SPB)</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin/report/purchase_order/project') }}" class="nav-link {{ Request::segment(2) == 'report' && Request::segment(3) == 'purchase_order' && Request::segment(4) == 'project' ? 'active' : '' }}">Project</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin/report/purchase_order/retail') }}" class="nav-link {{ Request::segment(2) == 'report' && Request::segment(3) == 'purchase_order' && Request::segment(4) == 'retail' ? 'active' : '' }}">Retail</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item nav-item-submenu {{ Request::segment(2) == 'report' && Request::segment(3) == 'delivery_order' ? 'nav-item-expanded nav-item-open' : '' }}">
                                <a href="#" class="nav-link">Delivery Order</a>
                                <ul class="nav nav-group-sub">
                                    <li class="nav-item">
                                        <a href="{{ url('admin/report/delivery_order/project') }}" class="nav-link {{ Request::segment(2) == 'report' && Request::segment(3) == 'delivery_order' && Request::segment(4) == 'project' ? 'active' : '' }}">Project</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin/report/delivery_order/invoice_delivery_status') }}" class="nav-link {{ Request::segment(2) == 'report' && Request::segment(3) == 'delivery_order' && Request::segment(4) == 'invoice_delivery_status' ? 'active' : '' }}">Invoice & Delivery</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin/report/delivery_order/payment') }}" class="nav-link {{ Request::segment(2) == 'report' && Request::segment(3) == 'delivery_order' && Request::segment(4) == 'payment' ? 'active' : '' }}">Payment</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin/report/delivery_order/retail') }}" class="nav-link {{ Request::segment(2) == 'report' && Request::segment(3) == 'delivery_order' && Request::segment(4) == 'retail' ? 'active' : '' }}">Retail</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item nav-item-submenu {{ Request::segment(2) == 'report' && Request::segment(3) == 'finance' ? 'nav-item-expanded nav-item-open' : '' }}">
                                <a href="#" class="nav-link">Finance</a>
                                <ul class="nav nav-group-sub">
                                    <li class="nav-item">
                                        <a href="{{ url('admin/report/finance/outstanding_a_r') }}" class="nav-link {{ Request::segment(2) == 'report' && Request::segment(3) == 'finance' && Request::segment(4) == 'outstanding_a_r' ? 'active' : '' }}">Outstanding A/R</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin/report/finance/outstanding_a_p') }}" class="nav-link {{ Request::segment(2) == 'report' && Request::segment(3) == 'finance' && Request::segment(4) == 'outstanding_a_p' ? 'active' : '' }}">Outstanding A/P</a>
                                    </li>
									<li class="nav-item">
                                        <a href="{{ url('admin/report/finance/outstanding_a_p_other') }}" class="nav-link {{ Request::segment(2) == 'report' && Request::segment(3) == 'finance' && Request::segment(4) == 'outstanding_a_p_other' ? 'active' : '' }}">
										Outstanding A/P Other
										</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item nav-item-submenu {{ Request::segment(2) == 'report' && Request::segment(3) == 'accounting' ? 'nav-item-expanded nav-item-open' : '' }}">
                                <a href="#" class="nav-link">Accounting</a>
                                <ul class="nav nav-group-sub">
									<li class="nav-item">
                                        <a href="{{ url('admin/report/accounting/profit_loss') }}" class="nav-link {{ Request::segment(2) == 'report' && Request::segment(3) == 'accounting' && Request::segment(4) == 'profit_loss' ? 'active' : '' }}">Profit & Loss</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin/report/accounting/profit_loss_comparison') }}" class="nav-link {{ Request::segment(2) == 'report' && Request::segment(3) == 'accounting' && Request::segment(4) == 'profit_loss_comparison' ? 'active' : '' }}">Profit & Loss Comparison</a>
                                    </li>
									<li class="nav-item">
                                        <a href="{{ url('admin/report/accounting/profit_loss_project') }}" class="nav-link {{ Request::segment(2) == 'report' && Request::segment(3) == 'accounting' && Request::segment(4) == 'profit_loss_project' ? 'active' : '' }}">Profit & Loss Project</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin/report/accounting/balance_sheet') }}" class="nav-link {{ Request::segment(2) == 'report' && Request::segment(3) == 'accounting' && Request::segment(4) == 'balance_sheet' ? 'active' : '' }}">Balance Sheet</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin/report/accounting/ledger') }}" class="nav-link {{ Request::segment(2) == 'report' && Request::segment(3) == 'accounting' && Request::segment(4) == 'ledger' ? 'active' : '' }}">Ledger</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin/report/accounting/trial_balance') }}" class="nav-link {{ Request::segment(2) == 'report' && Request::segment(3) == 'accounting' && Request::segment(4) == 'trial_balance' ? 'active' : '' }}">Trial Balance</a>
                                    </li>
									<li class="nav-item">
                                        <a href="{{ url('admin/report/accounting/cash_bank') }}" class="nav-link {{ Request::segment(2) == 'report' && Request::segment(3) == 'accounting' && Request::segment(4) == 'cash_bank' ? 'active' : '' }}">Cash & Bank</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin/report/accounting/aging_receivable') }}" class="nav-link {{ Request::segment(2) == 'report' && Request::segment(3) == 'accounting' && Request::segment(4) == 'aging_receivable' ? 'active' : '' }}">Aging Receivable</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin/report/accounting/aging_payable') }}" class="nav-link {{ Request::segment(2) == 'report' && Request::segment(3) == 'accounting' && Request::segment(4) == 'aging_payable' ? 'active' : '' }}">Aging Payable</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin/report/accounting/ar_customer') }}" class="nav-link {{ Request::segment(2) == 'report' && Request::segment(3) == 'accounting' && Request::segment(4) == 'aging_payable' ? 'active' : '' }}">A/R Customer</a>
                                    </li>
									<li class="nav-item">
                                        <a href="{{ url('admin/report/accounting/budgeting_comparison') }}" class="nav-link {{ Request::segment(2) == 'report' && Request::segment(3) == 'accounting' && Request::segment(4) == 'budgeting_comparison' ? 'active' : '' }}">Budgeting Comparison</a>
                                    </li>
									<li class="nav-item">
                                        <a href="{{ url('admin/report/accounting/project_comparison') }}" class="nav-link {{ Request::segment(2) == 'report' && Request::segment(3) == 'accounting' && Request::segment(4) == 'project_comparison' ? 'active' : '' }}">Project Comparison</a>
                                    </li>
									<li class="nav-item">
                                        <a href="{{ url('admin/report/accounting/cash_flow') }}" class="nav-link {{ Request::segment(2) == 'report' && Request::segment(3) == 'accounting' && Request::segment(4) == 'cash_flow' ? 'active' : '' }}">Cash Flow</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item nav-item-submenu {{ Request::segment(2) == 'hrd' ? 'nav-item-expanded nav-item-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="icon-folder-open2"></i>
                            <span>HRD</span>
                        </a>
                        <ul class="nav nav-group-sub" data-submenu-title="HRD">
                            <li class="nav-item">
                                <a href="{{ url('admin/hrd/employee') }}" class="nav-link {{ Request::segment(2) == 'hrd' && Request::segment(3) == 'employee' ? 'active' : '' }}">Employee</a>
                            </li>
							<li class="nav-item">
                                <a href="{{ url('admin/hrd/files') }}" class="nav-link {{ Request::segment(2) == 'hrd' && Request::segment(3) == 'files' ? 'active' : '' }}">Files</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('admin/hrd/attendance') }}" class="nav-link {{ Request::segment(2) == 'hrd' && Request::segment(3) == 'attendance' ? 'active' : '' }}">Attendance</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('admin/hrd/salary') }}" class="nav-link {{ Request::segment(2) == 'hrd' && Request::segment(3) == 'salary' ? 'active' : '' }}">Salary</a>
                            </li>
                            <!-- <li class="nav-item">
                                <a href="{{ url('admin/hrd/commission') }}" class="nav-link {{ Request::segment(2) == 'hrd' && Request::segment(3) == 'commission' ? 'active' : '' }}">Commission</a>
                            </li> -->
                            <li class="nav-item">
                                <a href="{{ url('admin/hrd/job_desc') }}" class="nav-link {{ Request::segment(2) == 'hrd' && Request::segment(3) == 'job_desc' ? 'active' : '' }}">Job Desc</a>
                            </li>
                        </ul>
                    </li>
					<li class="nav-item nav-item-submenu {{ Request::segment(2) == 'legal_docs' ? 'nav-item-expanded nav-item-open' : '' }}">
						<a href="#" class="nav-link">
                            <i class="icon-certificate"></i>
                            <span>Legal Docs</span>
                        </a>
                        <ul class="nav nav-group-sub" data-submenu-title="DOCS">
                            <li class="nav-item">
                                <a href="{{ url('admin/legal_docs/company') }}" class="nav-link {{ Request::segment(2) == 'legal_docs' && Request::segment(3) == 'company' ? 'active' : '' }}">Company & Personal</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('admin/legal_docs/insurance') }}" class="nav-link {{ Request::segment(2) == 'legal_docs' && Request::segment(3) == 'attendance' ? 'active' : '' }}">Insurance</a>
                            </li>
                        </ul>
                    </li>
					@if(in_array(1, session('bo_role')) || in_array(5, session('bo_role')))
					<li class="nav-item">
						<a href="{{ url('admin/downloads') }}" class="nav-link {{ Request::segment(2) == 'downloads' ? 'active' : '' }}">
                            <i class="icon-folder-download2"></i>
                            <span>Downloads</span>
                        </a>
                    </li>
					@endif
                    <li class="nav-item nav-item-submenu {{ Request::segment(2) == 'setting' ? 'nav-item-expanded nav-item-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="icon-gear"></i>
                            <span>Settings</span>
                        </a>
                        <ul class="nav nav-group-sub" data-submenu-title="Settings">
							<li class="nav-item">
                                <a href="{{ url('admin/setting/accounting') }}" class="nav-link {{ Request::segment(2) == 'setting' && Request::segment(3) == 'accounting' ? 'active' : '' }}">Accounting</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('admin/setting/user') }}" class="nav-link {{ Request::segment(2) == 'setting' && Request::segment(3) == 'user' ? 'active' : '' }}">User</a>
                            </li>
							<li class="nav-item">
                                <a href="{{ url('admin/setting/version') }}" class="nav-link {{ Request::segment(2) == 'setting' && Request::segment(3) == 'version' ? 'active' : '' }}">Version</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('admin/setting/2fa') }}" class="nav-link {{ Request::segment(2) == 'setting' && Request::segment(3) == '2fa' ? 'active' : '' }}">2FA</a>
                            </li>
                        </ul>
                    </li>
					@endif
                    @else
                    <li class="nav-item">
						<a href="{{ url('cogs_calculator') }}" class="nav-link {{ Request::segment(1) == 'cogs_calculator' ? 'active' : '' }}">
                            <i class="icon-calculator"></i>
                            <span>COGS Calculator</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
