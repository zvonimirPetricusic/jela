<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIngrediantMealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingrediant_meals', function (Blueprint $table) {
            $table->integer('meal_id')->unsigned();
            $table->integer('ingrediant_id')->unsigned();
            $table->foreign('ingrediant_id')->references('id')->on('ingrediants');
            $table->foreign('meal_id')->references('id')->on('meals');
            $table->primary(['ingrediant_id','meal_id']);
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
        Schema::dropIfExists('ingrediant_meals');
    }
}
