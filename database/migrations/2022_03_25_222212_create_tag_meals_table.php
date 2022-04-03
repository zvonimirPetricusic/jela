<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagMealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meals_tags', function (Blueprint $table) {
            $table->integer('tags_id')->unsigned();
            $table->integer('meals_id')->unsigned();

            $table->foreign('tags_id')->references('id')->on('tags');
            $table->foreign('meals_id')->references('id')->on('meals');
            $table->primary(['tags_id','meals_id']);
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
        Schema::dropIfExists('tag_meals');
    }
}
