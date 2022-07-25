<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->text("salad_1");
            $table->text("salad_2");
            $table->text("salad_3");
            $table->text("grains_1");
            $table->text("grains_2");
            $table->text("grains_3");
            $table->text("side_dish");
            $table->text("mixture");
            $table->text("vegan_mixture");
            $table->text("dessert");
            $table->date("date");
            $table->foreignId('ru_employee_id')->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menus');
    }
}
