<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_sales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index('user_id');
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('sales_id');
            $table->string('code', 50);
            $table->string('address', 255);
            $table->string('note', 255);
            $table->string('so_file', 255);
            $table->unsignedBigInteger('marketing_id');
            $table->unsignedBigInteger('approved_id');
            $table->double('delivery_cost', 20, 2);
            $table->double('cutting_cost', 20, 2);
            $table->double('misc_cost', 20, 2);
            $table->timestamps();

            $table->index(['project_id', 'sales_id', 'marketing_id', 'approved_id'], 'project_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_sales');
    }
}
