<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('category_id')->index();
            $table->unsignedBigInteger('division_id')->index();
            $table->unsignedBigInteger('surface_id')->nullable()->index();
            $table->unsignedBigInteger('color_id')->index();
            $table->unsignedBigInteger('pattern_id')->index();
            $table->unsignedBigInteger('loading_limit_id')->index();
            $table->unsignedBigInteger('buy_unit_id')->index();
            $table->unsignedBigInteger('stock_unit_id')->index();
            $table->unsignedBigInteger('selling_unit_id')->index();
            $table->string('image')->nullable();
            $table->string('code')->index('types_code_fulltext');
            $table->char('material', 1);
            $table->string('faces')->nullable();
            $table->double('length')->nullable();
            $table->double('width')->nullable();
            $table->double('height')->nullable();
            $table->double('weight');
            $table->double('thickness')->nullable();
            $table->double('conversion');
            $table->boolean('stockable')->default(false);
            $table->integer('min_stock');
            $table->integer('max_stock');
            $table->integer('small_stock');
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
        Schema::dropIfExists('types');
    }
}
