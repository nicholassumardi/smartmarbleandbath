<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectDeliveryProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_delivery_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('project_delivery_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('qty');
            $table->char('unit', 1);
            $table->integer('created_at');
            $table->integer('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_delivery_products');
    }
}
