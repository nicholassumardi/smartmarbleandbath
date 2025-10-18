<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectSaleShadingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_sale_shadings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('project_sale_id');
            $table->unsignedBigInteger('product_id');
            $table->string('warehouse_code')->nullable();
            $table->string('stock_code')->nullable();
            $table->string('code')->nullable();
            $table->integer('qty');
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
        Schema::dropIfExists('project_sale_shadings');
    }
}
