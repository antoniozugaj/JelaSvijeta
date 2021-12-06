<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trans_tags', function (Blueprint $table) {
            $table->id();
			$table->string('title');
            $table->timestamps();
			
			$table->unsignedBigInteger('tag_id');
			$table->unsignedBigInteger('language_id');
			
			$table->foreign('tag_id')->references('id')->on('tags')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('transTags');
    }
}
