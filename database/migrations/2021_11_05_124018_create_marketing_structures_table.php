<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketingStructuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketing_structures', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->index();
            $table->double('rental_cost')->default(0);
            $table->double('travel_sales_cost')->default(0);
            $table->double('marketing_cost')->default(0);
            $table->double('on_site_cost')->default(0);
            $table->double('storage_cost')->default(0);
            $table->double('fixed_cost')->default(0);
            $table->double('interest_in_payment')->default(0);
            $table->double('nett_profit')->default(0);
            $table->double('saving')->default(0);
            $table->double('sales_commission')->default(0);
            $table->double('middlemant_commission')->default(0);
            $table->double('project_commission')->default(0);
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
        Schema::dropIfExists('marketing_structures');
    }
}
