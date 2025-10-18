<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('country_id')->index();
            $table->unsignedBigInteger('city_id')->index();
            $table->string('code')->unique();
            $table->string('name');
            $table->unsignedBigInteger('customer_id')->index();
            $table->date('timeline');
            $table->string('manager');
            $table->string('consultant');
            $table->string('owner');
            $table->unsignedBigInteger('coa_id');
            $table->char('payment_method', 2);
            $table->char('term_payment', 2)->nullable();
            $table->char('supply_method', 1);
            $table->char('ppn', 1);
            $table->integer('progress')->default(0);
            $table->double('delivery_cost', 20, 2);
            $table->double('cutting_cost', 20, 2);
            $table->double('misc_cost', 20, 2);
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
        Schema::dropIfExists('projects');
    }
}
