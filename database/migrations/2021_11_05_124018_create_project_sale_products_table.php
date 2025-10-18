<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectSaleProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_sale_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('project_sale_id')->index('project_products_project_id_index');
            $table->unsignedBigInteger('product_id')->index('project_products_product_id_index');
            $table->string('area', 155);
            $table->string('spec', 255);
            $table->integer('qty');
            $table->double('cogs', 20, 2)->default(0);
            $table->double('price', 20, 2);
            $table->double('recommended_price', 20, 2)->default(0);
            $table->double('best_price', 20, 2);
            $table->double('discount', 20, 2)->nullable();
            $table->char('unit', 1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_sale_products');
    }
}
