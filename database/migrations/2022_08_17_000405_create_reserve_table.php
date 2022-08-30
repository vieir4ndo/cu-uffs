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
        Schema::create('reserves', function (Blueprint $table) {
            $table->id();
            $table->dateTime('begin');
            $table->dateTime('end');
            $table->text('description')->nullable();
            $table->integer('status')->default(0);
            $table->text('observation')->nullable();
            $table->foreignId('lessee_id')->constrained('users');
            $table->foreignId('room_id')->constrained('rooms');
            $table->foreignId('ccr_id')->nullable()->constrained('ccr');
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
