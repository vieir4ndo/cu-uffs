<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReserveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reserve', function (Blueprint $table) {
            $table->id();
            $table->foreignId('locator_id')->constrained('users');
            $table->foreignId('room_id')->constrained('rooms');
            //$table->foreignId('ccr_id')->constrained('ccr');
            $table->timestamps();
            $table->text('description');
            $table->integer('status');
            $table->text('observation');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reserve');
    }
}
