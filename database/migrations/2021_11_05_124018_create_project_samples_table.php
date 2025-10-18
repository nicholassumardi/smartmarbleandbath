<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectSamplesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_samples', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('project_id')->index('project_id');
            $table->string('code', 155);
            $table->date('sent_date')->nullable();
            $table->date('return_date')->nullable();
            $table->string('note', 255);
            $table->char('status', 1);
            $table->unsignedBigInteger('approved_by_1');
            $table->unsignedBigInteger('approved_by_2');
            $table->timestamp('returned_at')->nullable();
            $table->timestamps();

            $table->index(['approved_by_1', 'approved_by_2'], 'approved_by_1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_samples');
    }
}
