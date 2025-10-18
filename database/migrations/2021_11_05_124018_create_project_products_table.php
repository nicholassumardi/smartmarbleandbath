<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('project_id')->index();
            $table->unsignedBigInteger('product_id')->index();
            $table->string('area', 155);
            $table->string('spec', 255);
            $table->integer('qty');
            $table->double('cogs')->default(0);
            $table->double('price');
            $table->double('recommended_price')->default(0);
            $table->double('best_price', 20, 2);
            $table->double('discount')->nullable();
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
        Schema::dropIfExists('project_products');
    }
}
