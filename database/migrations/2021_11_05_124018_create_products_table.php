<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('type_id')->index();
            $table->unsignedBigInteger('company_id')->index();
            $table->unsignedBigInteger('hs_code_id')->index();
            $table->unsignedBigInteger('brand_id')->index();
            $table->unsignedBigInteger('country_id')->index();
            $table->unsignedBigInteger('supplier_id')->index();
            $table->unsignedBigInteger('grade_id')->index();
            $table->double('carton_pallet')->nullable();
            $table->double('carton_pcs')->nullable();
            $table->char('container_standart', 1);
            $table->double('container_stock');
            $table->double('container_max_stock');
            $table->text('description');
            $table->char('check', 1);
            $table->char('status', 1);
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
        Schema::dropIfExists('products');
    }
}
