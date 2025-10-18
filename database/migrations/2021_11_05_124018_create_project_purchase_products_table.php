<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectPurchaseProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_purchase_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('project_purchase_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('qty');
            $table->char('unit', 1);
            $table->double('price', 20, 2);
            $table->string('remark', 255);
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
        Schema::dropIfExists('project_purchase_products');
    }
}
