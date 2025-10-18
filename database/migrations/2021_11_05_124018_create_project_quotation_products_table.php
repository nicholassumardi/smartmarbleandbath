<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectQuotationProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_quotation_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('project_quotation_id')->index('project_quotations_products_project_quotation_id_index');
            $table->unsignedBigInteger('product_id')->index();
            $table->double('recommended_price', 20, 2);
            $table->double('best_price', 20, 2);
            $table->double('discount', 10, 2);
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
        Schema::dropIfExists('project_quotation_products');
    }
}
