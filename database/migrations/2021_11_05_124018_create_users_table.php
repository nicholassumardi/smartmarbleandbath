<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('photo')->nullable();
            $table->string('sign', 155)->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->char('branch', 1);
            $table->string('place_of_birth')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->char('gender', 1)->nullable();
            $table->char('marital_status', 1)->nullable();
            $table->char('blood_type', 5)->nullable();
            $table->char('religion', 1)->nullable();
            $table->char('id_type', 1)->nullable();
            $table->string('id_no', 30)->nullable();
            $table->string('postcode', 15)->nullable();
            $table->string('address_id', 255)->nullable();
            $table->string('address_residence', 255)->nullable();
            $table->string('npwp', 50)->nullable();
            $table->char('ispkp', 1)->nullable();
            $table->string('ptkp_type', 10)->nullable();
            $table->char('tax_type', 1)->nullable();
            $table->string('account_number')->nullable();
            $table->string('account_bank')->nullable();
            $table->string('account_name')->nullable();
            $table->timestamp('verification')->nullable();
            $table->string('token_device', 255);
            $table->char('status');
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
        Schema::dropIfExists('users');
    }
}
