<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePricingPoliciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pricing_policies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id')->index();
            $table->double('cogs')->default(0);
            $table->double('showroom_cost')->default(0);
            $table->double('sales_travel_cost')->default(0);
            $table->double('marketing_cost')->default(0);
            $table->double('interest')->default(0);
            $table->double('sales_commission')->default(0);
            $table->double('fixed_cost')->default(0);
            $table->double('nett_profit')->default(0);
            $table->double('saving')->default(0);
            $table->double('middlemant')->default(0);
            $table->double('project')->default(0);
            $table->double('on_site_cost')->default(0);
            $table->double('storage_cost')->default(0);
            $table->double('bottom_price')->default(0);
            $table->double('project_price')->default(0);
            $table->double('price_list')->default(0);
            $table->double('store_price_list')->default(0);
            $table->double('discount_retail_sales')->default(0);
            $table->double('discount_retail_manager')->default(0);
            $table->double('discount_retail_director')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pricing_policies');
    }
}
