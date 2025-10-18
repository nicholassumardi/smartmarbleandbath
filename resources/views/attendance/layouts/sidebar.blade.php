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
                            
                        </div>
                    </li>
                    <li class="nav-item-header">
                        <div class="text-uppercase font-size-xs line-height-xs">Main Navigation</div>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('attendance/code') }}" class="nav-link {{ Request::segment(2) == 'code'  ? 'active' : '' }}">
                            <i class="icon-qrcode"></i>
                            <span>Qr Code</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
