<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_deliveries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('project_id')->index();
            $table->unsignedBigInteger('project_sale_id')->index('project_sale_id');
            $table->string('code', 155);
            $table->unsignedBigInteger('city_id')->index();
            $table->string('receiver_name');
            $table->date('delivery_date');
            $table->string('email');
            $table->string('phone');
            $table->char('is_dropshipper', 1);
            $table->unsignedBigInteger('dropshipper_id');
            $table->text('address');
            $table->unsignedBigInteger('warehouse_id')->index('warehouse_id');
            $table->unsignedBigInteger('vendor_id')->index('vendor_id');
            $table->unsignedBigInteger('approved_by')->index('approved_by');
            $table->string('image', 255);
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
        Schema::dropIfExists('project_deliveries');
    }
}
