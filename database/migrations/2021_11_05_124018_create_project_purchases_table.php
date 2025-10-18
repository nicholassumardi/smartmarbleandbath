<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_purchases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('project_sale_id');
            $table->string('code', 255);
            $table->string('note', 255);
            $table->unsignedBigInteger('supplier_id');
            $table->string('production_lead_time', 100)->nullable();
            $table->date('estimated_delivery')->nullable();
            $table->date('estimated_arrival')->nullable();
            $table->string('factory_name', 155);
            $table->unsignedBigInteger('customer_id');
            $table->bigInteger('sales_id');
            $table->string('on_behalf', 155);
            $table->string('delivery_address', 500);
            $table->unsignedBigInteger('country_id');
            $table->unsignedBigInteger('city_id');
            $table->string('courier_method', 155);
            $table->string('pic', 155);
            $table->string('pic_no', 20);
            $table->string('payment_method', 100);
            $table->char('price', 1);
            $table->unsignedBigInteger('currency_id');
            $table->string('brand_on_box', 50);
            $table->string('sni', 50);
            $table->unsignedBigInteger('checked_by');
            $table->unsignedBigInteger('approved_by');
            $table->timestamps();

            $table->index(['project_sale_id', 'supplier_id', 'customer_id', 'sales_id', 'country_id', 'city_id'], 'project_sale_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_purchases');
    }
}
