<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectPaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_pays', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('project_id')->index();
            $table->unsignedBigInteger('project_sale_id')->index('project_sale_id');
            $table->string('code', 155);
            $table->string('image');
            $table->date('date');
            $table->date('due_date')->nullable();
            $table->double('nominal');
            $table->char('payment', 1);
            $table->char('payment_method', 1);
            $table->unsignedBigInteger('coa_id');
            $table->string('note', 255);
            $table->unsignedBigInteger('marketing_id');
            $table->unsignedBigInteger('approved_id');
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
        Schema::dropIfExists('project_pays');
    }
}
