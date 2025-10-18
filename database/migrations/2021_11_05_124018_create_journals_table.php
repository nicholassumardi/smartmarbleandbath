<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJournalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('journals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('journalable_type')->index();
            $table->unsignedBigInteger('journalable_id')->index();
            $table->unsignedBigInteger('coa_id');
            $table->char('type', 1);
            $table->double('nominal', 20, 2);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['journalable_type', 'journalable_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('journals');
    }
}
