<?php

namespace App\Providers;

use Carbon\Carbon;
use Xendit\Xendit;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(128);
        date_default_timezone_set('Asia/Jakarta');
        config(['app.locale' => 'id']);
        Paginator::useBootstrap();
        Xendit::setApiKey('xnd_production_H9vYVjHQg6nzYYCtY47tU3SAvoayRoTKQw5NnKNnXk0h2hzQXcaqL66Sd8D8y');
        // Xendit::setApiKey('xnd_development_YgsZZQ4o5XoZBBgF0EROorc0Lodit00TIZflitcCaeiWzwcGaYs1bCjJkEQeJ9');

        Relation::morphMap([
            'projects'   						=> 'App\Models\Project',
            'categories' 						=> 'App\Models\Category',
            'brands'     						=> 'App\Models\Brand',
            'cash_banks' 						=> 'App\Models\CashBank',
            'customers'  						=> 'App\Models\Customer',
            'users'      						=> 'App\Models\User',
			'project_quotations'				=> 'App\Models\ProjectQuotation',
			'project_samples'					=> 'App\Models\ProjectSample',
			'project_sales'						=> 'App\Models\ProjectSale',
			'project_pays'						=> 'App\Models\ProjectPay',
			'project_purchases'					=> 'App\Models\ProjectPurchase',
			'project_deliveries'				=> 'App\Models\ProjectDelivery',
			'project_sale_returns'				=> 'App\Models\ProjectSaleReturn',
			'project_payments'					=> 'App\Models\ProjectPayment',
			'project_warehouses'				=> 'App\Models\ProjectWarehouse',
			'project_purchase_returns'			=> 'App\Models\ProjectPurchaseReturn',
			'project_bills'						=> 'App\Models\ProjectBill',
			'payment_requests'					=> 'App\Models\PaymentRequest',
			'purchase_requests'					=> 'App\Models\PurchaseRequest',
			'purchase_request_payments' 		=> 'App\Models\PurchaseRequestPayment',
			'transfers' 						=> 'App\Models\Transfer',
			'receivable_payments' 				=> 'App\Models\ReceivablePayment',
			'project_main_payments' 			=> 'App\Models\ProjectMainPayment',
			'leave_requests' 					=> 'App\Models\LeaveRequest',
			'project_sales_temps'				=> 'App\Models\ProjectSaleTemp',
			'project_from_stocks'				=> 'App\Models\ProjectFromStock',
			'budgeting_projects'				=> 'App\Models\BudgetingProject',
			'project_tax_documents'				=> 'App\Models\ProjectTaxDocument',
			'balance_histories'					=> 'App\Models\BalanceHistory',
			'salaries'							=> 'App\Models\Salary',
			'attendances'						=> 'App\Models\Attendance',
			'purchase_request_main_payments'	=> 'App\Models\PurchaseRequestMainPayment',
			'samples'	                        => 'App\Models\Sample',
			'sample_purchases'	                => 'App\Models\SamplePurchase',
			'sample_purchase_returns'           => 'App\Models\SamplePurchaseReturn',
			'sample_deliveries'	                => 'App\Models\SampleDelivery',
			'sample_warehouses'	                => 'App\Models\SampleWarehouse',
            'sample_returns'                    => 'App\Models\SampleReturn',
            'service_costs'                     => 'App\Models\ServiceCost',
            'service_cost_payments'             => 'App\Models\ServiceCostPayment',
            'project_purchase_quotations'       => 'App\Models\ProjectPurchaseQuotation',
        ]);
    }
}
