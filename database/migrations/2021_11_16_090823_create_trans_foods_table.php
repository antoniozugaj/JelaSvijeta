<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransFoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trans_food', function (Blueprint $table) {
            $table->id();
			$table->string('title');
			$table->string('description');
            $table->timestamps();
			
			$table->unsignedBigInteger('food_id');
			$table->unsignedBigInteger('language_id');
			
			$table->foreign('food_id')->references('id')->on('food')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('language_id')->references('id')->on('languages')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transFoods');
    }
}
