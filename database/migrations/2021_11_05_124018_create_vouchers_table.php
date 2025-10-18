<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('voucherable_type')->nullable();
            $table->unsignedBigInteger('voucherable_id')->nullable();
            $table->string('code')->unique();
            $table->string('name');
            $table->double('minimum');
            $table->double('maximum');
            $table->integer('quota');
            $table->double('points');
            $table->double('percentage');
            $table->date('start_date');
            $table->date('finish_date');
            $table->text('terms');
            $table->char('type', 1);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['voucherable_type', 'voucherable_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vouchers');
    }
}
