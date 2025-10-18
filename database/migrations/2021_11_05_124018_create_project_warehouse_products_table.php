<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectWarehouseProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_warehouse_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('project_warehouse_id')->index('project_warehouse_id');
            $table->unsignedBigInteger('product_id')->index('product_id');
            $table->double('qty', 11, 2);
            $table->char('unit', 1);
            $table->double('qty_broken', 11, 2);
            $table->char('unit_broken', 1);
            $table->integer('created_at')->nullable();
            $table->integer('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_warehouse_products');
    }
}
