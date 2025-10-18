<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectSampleProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_sample_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('project_sample_id')->index('project_sample_id');
            $table->unsignedBigInteger('product_id')->index('project_samples_product_id_index');
            $table->integer('qty');
            $table->char('unit', 1);
            $table->char('size', 1);
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
        Schema::dropIfExists('project_sample_products');
    }
}
