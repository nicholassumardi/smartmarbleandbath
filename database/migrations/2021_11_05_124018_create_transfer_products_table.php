<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransferProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfer_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('transfer_id');
            $table->unsignedBigInteger('product_id');
            $table->string('warehouse_code_from', 155);
            $table->string('stock_code', 155);
            $table->string('code', 155);
            $table->double('qty', 20, 2);
            $table->char('unit', 1);
            $table->string('warehouse_code_to', 155);
            $table->timestamp('created_at')->useCurrentOnUpdate()->useCurrent();
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
        Schema::dropIfExists('transfer_products');
    }
}
