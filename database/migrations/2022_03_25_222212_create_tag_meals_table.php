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
        Schema::create('tag_meals', function (Blueprint $table) {
            $table->integer('tag_id')->unsigned();
            $table->integer('meal_id')->unsigned();

            $table->foreign('tag_id')->references('id')->on('tags');
            $table->foreign('meal_id')->references('id')->on('meals');
            $table->primary(['tag_id','meal_id']);
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
