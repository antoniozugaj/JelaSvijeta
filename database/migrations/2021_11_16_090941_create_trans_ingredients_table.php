<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransIngredientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trans_ingredients', function (Blueprint $table) {
            $table->id()->onUpdate('cascade')->onDelete('cascade');
			$table->string('title');
            $table->timestamps();
			
			$table->unsignedBigInteger('ingredient_id');
			$table->unsignedBigInteger('language_id');
			
			$table->foreign('ingredient_id')->references('id')->on('ingredients')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('trans_ingredients');
    }
}
